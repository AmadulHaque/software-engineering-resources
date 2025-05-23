import React, { useEffect, useRef, useState } from 'react';
import { Head, usePage } from '@inertiajs/react';
import Pusher from 'pusher-js';
import axios from 'axios'; // For API calls

// Define types for props if you are getting users list, etc.
interface User {
    id: number;
    name: string;
}

interface PageProps {
    users: User[];
    currentUser: User;
    pusherKey: string; // Ensure this is passed correctly from Inertia
    pusherCluster: string; // Ensure this is passed correctly from Inertia
}

const VideoChat: React.FC = () => {
    const { props } = usePage<PageProps>();
    // Destructure pusherKey and pusherCluster from props for direct use
    const { users, currentUser, pusherKey, pusherCluster } = props;

    const [localStream, setLocalStream] = useState<MediaStream | null>(null);
    const [remoteStream, setRemoteStream] = useState<MediaStream | null>(null);
    const [peerConnection, setPeerConnection] = useState<RTCPeerConnection | null>(null);
    const [callModalOpen, setCallModalOpen] = useState(false);
    const [incomingCallData, setIncomingCallData] = useState<any>(null); // Store offer & caller info
    const [callInProgress, setCallInProgress] = useState(false);
    const [targetUserId, setTargetUserId] = useState<number | null>(null); // User being called or calling
    const [pusherInstance, setPusherInstance] = useState<Pusher | null>(null); // Renamed to avoid confusion with `pusher` in closure
    const [channel, setChannel] = useState<any>(null); // Pusher channel

    const localVideoRef = useRef<HTMLVideoElement>(null);
    const remoteVideoRef = useRef<HTMLVideoElement>(null);

    // WebRTC Configuration (add STUN/TURN servers for real-world scenarios)
    const peerConnectionConfig = {
        iceServers: [
            { urls: 'stun:stun.l.google.com:19302' },
            // Add TURN servers if needed for NAT traversal
            // {
            //   urls: 'turn:your-turn-server.com:3478',
            //   username: 'user',
            //   credential: 'password'
            // }
        ],
    };

    // Refactored resetCallState to be stable across renders
    const resetCallState = useRef(() => {
        if (peerConnection) {
            peerConnection.close();
            setPeerConnection(null);
        }
        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
            setLocalStream(null);
            if(localVideoRef.current) localVideoRef.current.srcObject = null;
        }
        if (remoteStream) {
           remoteStream.getTracks().forEach(track => track.stop());
           setRemoteStream(null);
           if(remoteVideoRef.current) remoteVideoRef.current.srcObject = null;
        }

        setCallInProgress(false);
        setCallModalOpen(false);
        setIncomingCallData(null);
        setTargetUserId(null);
    });

    useEffect(() => {
        // Initialize Pusher
        // Use the pusherKey and pusherCluster from props directly
        const pusher = new Pusher(pusherKey, {
            cluster: pusherCluster,
            authEndpoint: '/pusher/auth',
            forceTLS: true,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content,
                }
            }
        });
        setPusherInstance(pusher);

        const userChannel = pusher.subscribe(`private-user.${currentUser.id}`);
        setChannel(userChannel);

        userChannel.bind('video-chat-event', async (data: any) => { // Mark as async for await calls
            console.log('Pusher event received:', data);
            switch (data.type) {
                case 'incoming-call':
                    if (callInProgress) { // Already in a call, perhaps send a busy signal
                        // TODO: Send busy signal
                        console.warn(`Incoming call from ${data.fromName} while already in a call. Sending busy signal.`);
                        // Send busy signal back to caller
                        axios.post(`/video/busy-signal`, { callerId: data.from })
                            .catch(e => console.error("Error sending busy signal:", e));
                        return;
                    }
                    setIncomingCallData({ sdp: data.sdp, from: data.from, fromName: data.fromName });
                    setTargetUserId(data.from); // Set target user ID for incoming call
                    setCallModalOpen(true);
                    break;
                case 'call-accepted':
                    if (peerConnection && data.sdp) {
                        console.log('Call accepted by remote, setting remote description');
                        try {
                            // Ensure data.sdp is correctly structured { type: 'answer', sdp: '...' }
                            await peerConnection.setRemoteDescription(new RTCSessionDescription(data.sdp));
                            setCallInProgress(true);
                            console.log('Remote description set successfully on call accepted.');
                        } catch (e) {
                            console.error("Error setting remote description on call accepted:", e);
                            alert("Failed to connect call: Remote SDP error.");
                            resetCallState.current(); // Reset on error
                        }
                    } else {
                        console.warn("Received 'call-accepted' but peerConnection is null or data.sdp is missing.");
                        resetCallState.current(); // Reset if state is inconsistent
                    }
                    break;
                case 'ice-candidate':
                    if (peerConnection && data.candidate) {
                        console.log('Received ICE candidate:', data.candidate);
                        try {
                            await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
                            console.log('ICE candidate added successfully.');
                        } catch (e) {
                           console.error("Error adding received ICE candidate:", e);
                           // This error can sometimes happen if an ICE candidate arrives
                           // before setRemoteDescription has completed, or if it's a duplicate.
                           // Often safe to ignore if the call eventually connects, but log for debugging.
                        }
                    } else {
                        console.warn("Received ICE candidate but peerConnection is null or data.candidate is missing.");
                    }
                    break;
                case 'call-declined':
                    alert(`${data.fromName || 'Remote user'} declined the call.`);
                    resetCallState.current();
                    break;
                case 'call-ended':
                    alert(`${data.fromName || 'Remote user'} ended the call.`);
                    resetCallState.current();
                    break;
                case 'busy-signal': // Handle busy signal
                    alert(`${data.fromName || 'Remote user'} is busy.`);
                    resetCallState.current();
                    break;
                default:
                    console.warn('Unknown Pusher event type:', data.type);
            }
        });

        return () => {
            if (pusher) {
                pusher.unsubscribe(`private-user.${currentUser.id}`);
                pusher.disconnect();
                console.log('Pusher disconnected and channel unsubscribed.');
            }
            resetCallState.current(); // Clean up streams and connections on unmount
        };
    }, [currentUser.id, callInProgress, peerConnection, pusherKey, pusherCluster]); // Added pusherKey, pusherCluster to dependency array

    const getMedia = async (): Promise<MediaStream | null> => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            setLocalStream(stream);
            if (localVideoRef.current) {
                localVideoRef.current.srcObject = stream;
            }
            console.log('Local media stream obtained.');
            return stream;
        } catch (error) {
            console.error('Error accessing media devices.', error);
            alert('Could not access your camera/microphone. Please check permissions.');
            return null;
        }
    };

    const createPeerConnection = (stream: MediaStream): RTCPeerConnection => {
        console.log('Creating new RTCPeerConnection.');
        const pc = new RTCPeerConnection(peerConnectionConfig);

        pc.onicecandidate = (event) => {
            if (event.candidate && targetUserId) { // targetUserId must be set for this to work
                console.log('Sending ICE candidate:', event.candidate);
                axios.post(`/video/candidate`, {
                    candidate: event.candidate,
                    toUserId: targetUserId,
                }).catch(e => console.error("Error sending ICE candidate:", e));
            } else if (event.candidate && !targetUserId) {
                console.warn("ICE candidate generated but targetUserId is null. Candidate will not be sent:", event.candidate);
            }
        };

        pc.ontrack = (event) => {
            console.log('Remote track received:', event.track.kind);
            if (remoteVideoRef.current && event.streams && event.streams[0]) {
                setRemoteStream(event.streams[0]);
                remoteVideoRef.current.srcObject = event.streams[0];
            }
        };

        // Add both audio and video tracks
        stream.getTracks().forEach(track => {
            console.log(`Adding track: ${track.kind} - ${track.id}`);
            pc.addTrack(track, stream)
        });
        setPeerConnection(pc);
        return pc;
    };

    const initiateCall = async (userIdToCall: number) => {
        if (callInProgress || callModalOpen) {
            alert('Already in a call or receiving an incoming call.');
            return;
        }

        // Get media first if not already obtained
        const stream = localStream || await getMedia();
        if (!stream) {
            alert('Cannot initiate call without camera and microphone access.');
            return; // Failed to get media
        }

        const pc = createPeerConnection(stream);
        setTargetUserId(userIdToCall); // Set target user ID immediately

        try {
            const offer = await pc.createOffer();
            await pc.setLocalDescription(offer);

            console.log('Sending offer to user:', userIdToCall, 'SDP:', offer);
            await axios.post(`/video/call-user/${userIdToCall}`, { sdp: pc.localDescription });
            // UI update: "Calling user..." - you might want a state here for "dialing"
            alert(`Calling ${users.find(u => u.id === userIdToCall)?.name || 'user'}...`);
        } catch (error) {
            console.error('Error initiating call:', error);
            alert('Failed to initiate call. See console for details.');
            resetCallState.current();
        }
    };

    const handleAcceptCall = async () => {
        if (!incomingCallData || !incomingCallData.sdp) {
            console.error("No incoming call data to accept.");
            setCallModalOpen(false);
            return;
        }

        const stream = await getMedia();
        if (!stream) {
            handleDeclineCall(true); // Pass a flag to indicate internal decline due to media access
            return;
        }

        // Ensure targetUserId is set from incoming call
        setTargetUserId(incomingCallData.from);

        const pc = createPeerConnection(stream); // Create PC *after* getting media and setting targetUserId

        try {
            console.log("--- Debugging Incoming SDP for Acceptance ---");
            console.log("Full incomingCallData:", incomingCallData);
            console.log("incomingCallData.sdp (raw):", incomingCallData.sdp);
            console.log("typeof incomingCallData.sdp:", typeof incomingCallData.sdp);
            // Confirm the inner 'sdp' string is present and a string
            console.log("Is incomingCallData.sdp.sdp a string?", typeof incomingCallData.sdp?.sdp === 'string');
            console.log("--- End Debugging Incoming SDP for Acceptance ---");

            // The RTCSessionDescription constructor expects an object like { type: 'offer', sdp: '...' }
            // Given your previous logs, incomingCallData.sdp *is* this object.
            const remoteDescription = new RTCSessionDescription(incomingCallData.sdp);
            await pc.setRemoteDescription(remoteDescription);
            console.log('Successfully set remote description (offer) in handleAcceptCall.');

            const answer = await pc.createAnswer();
            await pc.setLocalDescription(answer);

            console.log('Sending answer to user:', incomingCallData.from, 'SDP:', answer);
            await axios.post(`/video/accept-call`, {
                sdp: pc.localDescription,
                callerId: incomingCallData.from,
            });
            setCallInProgress(true);
            setCallModalOpen(false);
            setIncomingCallData(null); // Clear incoming data after processing
        } catch (error) {
            console.error('Error accepting call:', error);
            alert(`Failed to accept call: ${error instanceof Error ? error.message : String(error)}. Please check console.`);
            resetCallState.current(); // Reset state on error
        }
    };

    const handleDeclineCall = async (internalDecline = false) => {
        if (incomingCallData && incomingCallData.from && !internalDecline) {
            console.log(`Declining call from ${incomingCallData.fromName || 'remote user'}.`);
            await axios.post(`/video/decline-call`, {
                callerId: incomingCallData.from,
            }).catch(e => console.error("Error sending decline signal:", e));
        }
        setCallModalOpen(false);
        setIncomingCallData(null);
        // Only reset full state if a call was actually in progress or if it was an internal decline (e.g., media failed)
        if (callInProgress || internalDecline) {
            resetCallState.current();
        }
    };

    const handleEndCall = async () => {
        if (targetUserId) {
            console.log(`Ending call with user: ${targetUserId}`);
            await axios.post(`/video/end-call`, {
                toUserId: targetUserId,
            }).catch(e => console.error("Error sending end call signal:", e));
        }
        resetCallState.current(); // Always reset state after attempting to end call
        alert('Call ended.');
    };

    // Initial media access (optional, can be done on call initiation)
    useEffect(() => {
        // Option to pre-load media, uncomment if desired:
        // getMedia();

        // The cleanup function should use the ref for resetCallState
        return () => {
            console.log('VideoChat component unmounting. Cleaning up...');
            // This is handled by the Pusher useEffect cleanup, but ensuring local cleanup too
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
            }
            // Ensure any active peer connection is closed on unmount
            if (peerConnection) {
                peerConnection.close();
            }
        };
    }, [localStream, peerConnection]); // Depend on localStream and peerConnection for cleanup


    return (
        <>
            <Head title="Video Chat" />
            <div className="container mx-auto p-4">
                <h1 className="text-2xl font-bold mb-4">Video Chat - Welcome {currentUser.name}</h1>

                {/* Users List to Call */}
                {!callInProgress && !callModalOpen && (
                    <div className="mb-4">
                        <h2 className="text-xl">Users to call:</h2>
                        <ul>
                            {users.length > 0 ? (
                                users.filter(user => user.id !== currentUser.id).map(user => (
                                    <li key={user.id} className="my-2 flex items-center">
                                        <span className="text-lg">{user.name}</span>
                                        <button
                                            onClick={() => initiateCall(user.id)}
                                            className="ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                                            disabled={callInProgress || callModalOpen} // Disable if a call is active or incoming
                                        >
                                            Call
                                        </button>
                                    </li>
                                ))
                            ) : (
                                <p>No other users available to call.</p>
                            )}
                        </ul>
                    </div>
                )}

                {/* Call Modal for Incoming Call */}
                {callModalOpen && incomingCallData && (
                    <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex justify-center items-center z-50">
                        <div className="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-center">
                            <h3 className="text-xl font-semibold mb-4">Incoming Call from {incomingCallData.fromName || 'Unknown'}</h3>
                            <div className="flex justify-center gap-4">
                                <button
                                    onClick={handleAcceptCall}
                                    className="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-200"
                                >
                                    Accept
                                </button>
                                <button
                                    onClick={() => handleDeclineCall()}
                                    className="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-200"
                                >
                                    Decline
                                </button>
                            </div>
                        </div>
                    </div>
                )}

                {/* Video Elements */}
                <div className="flex flex-col md:flex-row gap-4 mt-8">
                    <div className="flex-1 border rounded-lg overflow-hidden shadow-md">
                        <h3 className="text-lg font-medium p-2 bg-gray-100 border-b">My Video</h3>
                        <video ref={localVideoRef} autoPlay playsInline muted className="w-full h-64 md:h-80 object-cover bg-black"></video>
                    </div>
                    <div className="flex-1 border rounded-lg overflow-hidden shadow-md">
                        <h3 className="text-lg font-medium p-2 bg-gray-100 border-b">Remote Video</h3>
                        {callInProgress ? (
                            <video ref={remoteVideoRef} autoPlay playsInline className="w-full h-64 md:h-80 object-cover bg-black"></video>
                        ) : (
                            <div className="w-full h-64 md:h-80 bg-gray-200 flex items-center justify-center text-gray-500">
                                No remote video during standby
                            </div>
                        )}
                    </div>
                </div>

                {/* Call Controls */}
                <div className="mt-6 flex justify-center gap-4">
                    {callInProgress && (
                         <button
                            onClick={handleEndCall}
                            className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-5 rounded-full shadow-lg transition duration-200"
                        >
                            End Call
                        </button>
                    )}
                     {!localStream && !callInProgress && (
                         <button
                            onClick={getMedia}
                            className="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-5 rounded-full shadow-lg transition duration-200"
                        >
                            Start My Camera
                        </button>
                    )}
                </div>

            </div>
        </>
    );
};

export default VideoChat;
