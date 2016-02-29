CREATE TABLE IF NOT EXISTS recipe (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(256) NOT NULL,
    description     TEXT NOT NULL,
    
    date_created    DATETIME NOT NULL,
    date_modified   DATETIME
);

CREATE TABLE IF NOT EXISTS recipe_ingredient (
    recipe_id       INT NOT NULL,
    quantity        INT NULL,
    description     VARCHAR(256) NOT NULL,
    units           VARCHAR(16)
    
    FOREIGN KEY recipe_ingredient_recipe_fk REFERENCES recipe(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS recipe_step (
    recipe_id       INT NOT NULL,
    step_order      INT NOT NULL,
    content         TEXT NOT NULL,
    
    FOREIGN KEY recipe_step_recipe_fk REFERENCES recipe(id) ON DELETE CASCADE
);