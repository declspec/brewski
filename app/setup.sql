CREATE TABLE IF NOT EXISTS recipe (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    parent_id       INT,
    next_version_id INT,
    name            VARCHAR(256) NOT NULL,
    description     TEXT NOT NULL,
    
    notes           VARCHAR(2048),
    estimated_time  VARCHAR(64),
    
    date_created    DATETIME NOT NULL,
    date_modified   DATETIME,
    
    FOREIGN KEY(parent_id) REFERENCES recipe(id) ON DELETE SET NULL,
    FOREIGN KEY(next_version_id) REFERENCES recipe(id) ON DELETE SET NULL
) Engine=InnoDB;

CREATE TABLE IF NOT EXISTS recipe_ingredient (
    recipe_id       INT NOT NULL,
    quantity        VARCHAR(64),
    description     VARCHAR(256) NOT NULL,

    FOREIGN KEY(recipe_id) REFERENCES recipe(id) ON DELETE CASCADE
) Engine=InnoDB;

CREATE TABLE IF NOT EXISTS recipe_step (
    recipe_id       INT NOT NULL,
    step_order      INT NOT NULL,
    content         TEXT,
    
    FOREIGN KEY(recipe_id) REFERENCES recipe(id) ON DELETE CASCADE
) Engine=InnoDB;

CREATE TABLE IF NOT EXISTS brew (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    recipe_id       INT NOT NULL,
    current_step    VARCHAR(32),
    notes           TEXT,
    initial_sg      DECIMAL(12, 4),
    final_sg        DECIMAL(12, 4),
    
    date_brewed     DATETIME,
    date_bottled    DATETIME,
    
    date_created    DATETIME NOT NULL,
    date_modified   DATETIME,
    
    FOREIGN KEY(recipe_id) REFERENCES recipe(id) ON DELETE NO ACTION
) Engine=InnoDB;