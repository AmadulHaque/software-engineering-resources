<?php

// Purpose: Separates an abstraction from its implementation so that both can vary independently.
// Real-world analogy: Think of a TV remote control. The remote (abstraction) works with different TV brands (implementations).

// Implementation interface
interface DeviceImplementation {
    public function turnOn();
    public function turnOff();
    public function setVolume($volume);
    public function setChannel($channel);
}

// Concrete Implementations
class TVDevice implements DeviceImplementation {
    private $isOn = false;
    private $volume = 50;
    private $channel = 1;
    
    public function turnOn() {
        $this->isOn = true;
        echo "TV turned on\n";
    }
    
    public function turnOff() {
        $this->isOn = false;
        echo "TV turned off\n";
    }
    
    public function setVolume($volume) {
        $this->volume = $volume;
        echo "TV volume set to $volume\n";
    }
    
    public function setChannel($channel) {
        $this->channel = $channel;
        echo "TV channel set to $channel\n";
    }
}

class RadioDevice implements DeviceImplementation {
    private $isOn = false;
    private $volume = 30;
    private $channel = 99.5;
    
    public function turnOn() {
        $this->isOn = true;
        echo "Radio turned on\n";
    }
    
    public function turnOff() {
        $this->isOn = false;
        echo "Radio turned off\n";
    }
    
    public function setVolume($volume) {
        $this->volume = $volume;
        echo "Radio volume set to $volume\n";
    }
    
    public function setChannel($channel) {
        $this->channel = $channel;
        echo "Radio tuned to $channel FM\n";
    }
}

// Abstraction
abstract class RemoteControl {
    protected $device;
    
    public function __construct(DeviceImplementation $device) {
        $this->device = $device;
    }
    
    abstract public function power();
    abstract public function volumeUp();
    abstract public function volumeDown();
    abstract public function channelUp();
    abstract public function channelDown();
}

// Refined Abstraction
class BasicRemoteControl extends RemoteControl {
    private $power = false;
    private $volume = 50;
    private $channel = 1;
    
    public function power() {
        $this->power = !$this->power;
        if ($this->power) {
            $this->device->turnOn();
        } else {
            $this->device->turnOff();
        }
    }
    
    public function volumeUp() {
        $this->volume += 10;
        $this->device->setVolume($this->volume);
    }
    
    public function volumeDown() {
        $this->volume -= 10;
        $this->device->setVolume($this->volume);
    }
    
    public function channelUp() {
        $this->channel += 1;
        $this->device->setChannel($this->channel);
    }
    
    public function channelDown() {
        $this->channel -= 1;
        $this->device->setChannel($this->channel);
    }
}

// Advanced remote with additional functionality
class AdvancedRemoteControl extends BasicRemoteControl {
    public function mute() {
        $this->device->setVolume(0);
        echo "Muted!\n";
    }
    
    public function goToChannel($channel) {
        $this->device->setChannel($channel);
    }
}

// Client code
$tv = new TVDevice();
$basicRemote = new BasicRemoteControl($tv);
echo "Basic remote with TV:\n";
$basicRemote->power();
$basicRemote->volumeUp();
$basicRemote->channelUp();
$basicRemote->power();

$radio = new RadioDevice();
$advancedRemote = new AdvancedRemoteControl($radio);
echo "\nAdvanced remote with Radio:\n";
$advancedRemote->power();
$advancedRemote->volumeUp();
$advancedRemote->goToChannel(101.5);
$advancedRemote->mute();
$advancedRemote->power();
