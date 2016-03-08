CREATE TABLE IF NOT EXISTS recipe (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    parent_id       INT,
    name            VARCHAR(256) NOT NULL,
    description     TEXT NOT NULL,
    
    notes           VARCHAR(2048),
    estimated_time  VARCHAR(64),
    
    date_created    DATETIME NOT NULL,
    date_modified   DATETIME,
    
    FOREIGN KEY(parent_id) REFERENCES recipe(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS recipe_ingredient (
    recipe_id       INT NOT NULL,
    quantity        VARCHAR(64),
    description     VARCHAR(256) NOT NULL,

    FOREIGN KEY(recipe_id) REFERENCES recipe(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS recipe_step (
    recipe_id       INT NOT NULL,
    step_order      INT NOT NULL,
    content         TEXT,
    
    FOREIGN KEY(recipe_id) REFERENCES recipe(id) ON DELETE CASCADE
);