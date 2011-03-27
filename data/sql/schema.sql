CREATE TABLE filiere (id BIGINT AUTO_INCREMENT, url VARCHAR(255), nom VARCHAR(255), description TEXT, logo VARCHAR(255), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE promotion (id BIGINT AUTO_INCREMENT, url VARCHAR(255), nom VARCHAR(255), description TEXT, filiere_id BIGINT NOT NULL, id_tree VARCHAR(255), id_piano_day VARCHAR(255) DEFAULT '0,1,2,3,4', width BIGINT DEFAULT 800, height BIGINT DEFAULT 600, INDEX filiere_id_idx (filiere_id), PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE promotion ADD CONSTRAINT promotion_filiere_id_filiere_id FOREIGN KEY (filiere_id) REFERENCES filiere(id);
