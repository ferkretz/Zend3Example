CREATE DATABASE ComputerTest;

USE ComputerTest;

CREATE TABLE Processors (
    id              int(10)      unsigned NOT NULL auto_increment,
    name            varchar(64)           NOT NULL default '',
    description     text,
    PRIMARY KEY (id),
            KEY (name)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE Memories (
    id              int(10)      unsigned NOT NULL auto_increment,
    name            varchar(64)           NOT NULL default '',
    dimm            varchar(16)           NOT NULL default 'DDR4 SDRAM',
    description     text,
    PRIMARY KEY (id),
            KEY (name),
            KEY (dimm)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE Computers (
    id              int(10)      unsigned NOT NULL auto_increment,
    name            varchar(64)           NOT NULL default '',
    description     text,
    processorId     int(10)      unsigned default NULL,
    dimm            varchar(16)           NOT NULL default 'DDR4 SDRAM',
    PRIMARY KEY (id),
            KEY (name),
            KEY (processorId),
    FOREIGN KEY (processorId) REFERENCES Processors(id) ON DELETE RESTRICT
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE ComputerMemoryRelations (
    computerId      int(10)      unsigned NOT NULL default '0',
    memoryId        int(10)      unsigned NOT NULL default '0',
    PRIMARY KEY (computerId, memoryId),
    FOREIGN KEY (computerId) REFERENCES Computers(id) ON DELETE CASCADE,
    FOREIGN KEY (memoryId) REFERENCES Memories(id) ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

insert into Processors (name, description) values ('Intel i7-7920HQ', 'Intel® Core™ i7-7920HQ Processor');
insert into Processors (name, description) values ('Intel i7-7560U', 'Intel® Core™ i7-7560U Processor');

insert into Memories (name, dimm, description) values ('HyperX 8GB',  'DDR3 SDRAM', 'HyperX Fury Memory (8GB - DDR3 1866MHz CL10 DIMM)');
insert into Memories (name, dimm, description) values ('HyperX 4GB',  'DDR3 SDRAM', 'HyperX Fury Memory (4GB - DDR3 1866MHz CL10 DIMM)');
insert into Memories (name, dimm, description) values ('HyperX 16GB', 'DDR4 SDRAM', 'HyperX Fury Memory (16GB - DDR4 2400MHz CL15 DIMM)');
insert into Memories (name, dimm, description) values ('HyperX 8GB',  'DDR4 SDRAM', 'HyperX Fury Memory (8GB - DDR4 2400MHz CL15 DIMM)');

insert into Computers (name, description, processorId, dimm) values ('Expert Computer', 'Intel i7-7920HQ, 2xDDR4', '1', 'DDR4 SDRAM');
insert into ComputerMemoryRelations (computerID, memoryId) values ('1', '3');
insert into ComputerMemoryRelations (computerID, memoryId) values ('1', '4');


