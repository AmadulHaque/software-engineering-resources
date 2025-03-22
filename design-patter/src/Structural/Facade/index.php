<?php

// Purpose: Provides a simplified interface to a complex subsystem, making it easier to use.
// Real-world analogy: A car's dashboard provides a simple interface to all the complex systems
// of the car.


// Complex subsystem classes
class CPU {
    public function freeze() {
        echo "CPU: Freezing processor...\n";
    }
    
    public function jump($position) {
        echo "CPU: Jumping to position $position...\n";
    }
    
    public function execute() {
        echo "CPU: Executing instructions...\n";
    }
}

class Memory {
    public function load($position, $data) {
        echo "Memory: Loading data '$data' at position $position...\n";
    }
}

class HardDrive {
    public function read($sector, $size) {
        echo "HardDrive: Reading $size bytes from sector $sector...\n";
        return "DATA_FROM_SECTOR_$sector";
    }
}

// Facade
class ComputerFacade {
    private $cpu;
    private $memory;
    private $hardDrive;
    
    public function __construct() {
        $this->cpu = new CPU();
        $this->memory = new Memory();
        $this->hardDrive = new HardDrive();
    }
    
    public function start() {
        echo "Starting computer...\n";
        $this->cpu->freeze();
        $this->memory->load(0, "BOOT_DATA");
        $this->cpu->jump(0);
        $this->cpu->execute();
        echo "Computer started successfully!\n";
    }
    
    public function shutdown() {
        echo "Shutting down computer...\n";
        // Complex shutdown operations simplified
        echo "Computer shut down successfully!\n";
    }
    
    public function runProgram($programName) {
        echo "Running $programName...\n";
        
        // Complex sequence of operations simplified
        $this->cpu->freeze();
        $programData = $this->hardDrive->read(100, 1024);
        $this->memory->load(50, $programData);
        $this->cpu->jump(50);
        $this->cpu->execute();
        
        echo "$programName executed successfully!\n";
    }
}

// Client code
$computer = new ComputerFacade();
$computer->start();
$computer->runProgram("Calculator");
$computer->runProgram("Browser");
$computer->shutdown();
