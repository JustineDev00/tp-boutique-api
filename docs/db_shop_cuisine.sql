CREATE TABLE categorie(
   Id_categorie VARCHAR(255),
   name VARCHAR(50),
   image VARCHAR(50),
   is_deleted BOOLEAN,
   PRIMARY KEY(Id_categorie)
);

CREATE TABLE account(
   Id_account VARCHAR(255),
   is_deleted BOOLEAN,
   email VARCHAR(50),
   password VARCHAR(50),
   PRIMARY KEY(Id_account)
);

CREATE TABLE role(
   Id_Role VARCHAR(255),
   title VARCHAR(50),
   is_deleted VARCHAR(50),
   PRIMARY KEY(Id_Role)
);

CREATE TABLE cat_image(
   Id_image VARCHAR(255),
   src VARCHAR(255),
   alt VARCHAR(255),
   is_deleted BOOLEAN,
   Id_categorie VARCHAR(255),
   PRIMARY KEY(Id_image),
   FOREIGN KEY(Id_categorie) REFERENCES categorie(Id_categorie)
);

CREATE TABLE article(
   Id_article VARCHAR(255),
   title VARCHAR(50),
   content VARCHAR(50),
   price DECIMAL(15,2),
   updated_at DATE,
   created_at DATE,
   is_deleted BOOLEAN,
   stock INT,
   Id_categorie VARCHAR(255),
   PRIMARY KEY(Id_article),
   FOREIGN KEY(Id_categorie) REFERENCES categorie(Id_categorie)
);

CREATE TABLE app_user(
   Id_app_user VARCHAR(255),
   name VARCHAR(255),
   is_deleted BOOLEAN,
   Id_Role VARCHAR(255),
   Id_account VARCHAR(255),
   PRIMARY KEY(Id_app_user),
   UNIQUE(Id_account),
   FOREIGN KEY(Id_Role) REFERENCES role(Id_Role),
   FOREIGN KEY(Id_account) REFERENCES account(Id_account)
);

CREATE TABLE art_image(
   Id_image VARCHAR(255),
   src VARCHAR(255),
   alt VARCHAR(255),
   is_deleted BOOLEAN,
   Id_article VARCHAR(255),
   PRIMARY KEY(Id_image),
   FOREIGN KEY(Id_article) REFERENCES article(Id_article)
);

CREATE TABLE adresse(
   Id_adresse VARCHAR(255),
   numero_voie INT,
   nom_voie VARCHAR(100),
   code_postal VARCHAR(150),
   ville VARCHAR(150),
   pays VARCHAR(150),
   Id_app_user VARCHAR(255),
   PRIMARY KEY(Id_adresse),
   FOREIGN KEY(Id_app_user) REFERENCES app_user(Id_app_user)
);

CREATE TABLE commande(
   Id_commande VARCHAR(255),
   total DECIMAL(15,2),
   created_at DATETIME,
   validated_at DATETIME,
   expires_at DATETIME,
   delivered_at DATETIME,
   is_deleted BOOLEAN,
   Id_adresse VARCHAR(255),
   Id_app_user VARCHAR(255),
   PRIMARY KEY(Id_commande),
   FOREIGN KEY(Id_adresse) REFERENCES adresse(Id_adresse),
   FOREIGN KEY(Id_app_user) REFERENCES app_user(Id_app_user)
);

CREATE TABLE article_commande(
   Id_article VARCHAR(255),
   Id_commande VARCHAR(255),
   quantite INT,
   total_price DECIMAL(15,2),
   is_deleted BOOLEAN,
   PRIMARY KEY(Id_article, Id_commande),
   FOREIGN KEY(Id_article) REFERENCES article(Id_article),
   FOREIGN KEY(Id_commande) REFERENCES commande(Id_commande)
);
CREATE TABLE categorie(
   Id_categorie VARCHAR(255),
   name VARCHAR(50),
   image VARCHAR(50),
   is_deleted BOOLEAN,
   PRIMARY KEY(Id_categorie)
);

CREATE TABLE account(
   Id_account VARCHAR(255),
   is_deleted BOOLEAN,
   email VARCHAR(50),
   password VARCHAR(50),
   PRIMARY KEY(Id_account)
);

CREATE TABLE role(
   Id_Role VARCHAR(255),
   title VARCHAR(50),
   is_deleted VARCHAR(50),
   PRIMARY KEY(Id_Role)
);

CREATE TABLE cat_image(
   Id_image VARCHAR(255),
   src VARCHAR(255),
   alt VARCHAR(255),
   is_deleted BOOLEAN,
   Id_categorie VARCHAR(255),
   PRIMARY KEY(Id_image),
   FOREIGN KEY(Id_categorie) REFERENCES categorie(Id_categorie)
);

CREATE TABLE article(
   Id_article VARCHAR(255),
   title VARCHAR(50),
   content VARCHAR(50),
   price DECIMAL(15,2),
   updated_at DATE,
   created_at DATE,
   is_deleted BOOLEAN,
   stock INT,
   Id_categorie VARCHAR(255),
   PRIMARY KEY(Id_article),
   FOREIGN KEY(Id_categorie) REFERENCES categorie(Id_categorie)
);

CREATE TABLE app_user(
   Id_app_user VARCHAR(255),
   name VARCHAR(255),
   is_deleted BOOLEAN,
   Id_Role VARCHAR(255),
   Id_account VARCHAR(255),
   PRIMARY KEY(Id_app_user),
   UNIQUE(Id_account),
   FOREIGN KEY(Id_Role) REFERENCES role(Id_Role),
   FOREIGN KEY(Id_account) REFERENCES account(Id_account)
);

CREATE TABLE art_image(
   Id_image VARCHAR(255),
   src VARCHAR(255),
   alt VARCHAR(255),
   is_deleted BOOLEAN,
   Id_article VARCHAR(255),
   PRIMARY KEY(Id_image),
   FOREIGN KEY(Id_article) REFERENCES article(Id_article)
);

CREATE TABLE adresse(
   Id_adresse VARCHAR(255),
   numero_voie INT,
   nom_voie VARCHAR(100),
   code_postal VARCHAR(150),
   ville VARCHAR(150),
   pays VARCHAR(150),
   Id_app_user VARCHAR(255),
   PRIMARY KEY(Id_adresse),
   FOREIGN KEY(Id_app_user) REFERENCES app_user(Id_app_user)
);

CREATE TABLE commande(
   Id_commande VARCHAR(255),
   total DECIMAL(15,2),
   created_at DATETIME,
   validated_at DATETIME,
   expires_at DATETIME,
   delivered_at DATETIME,
   is_deleted BOOLEAN,
   Id_adresse VARCHAR(255),
   Id_app_user VARCHAR(255),
   PRIMARY KEY(Id_commande),
   FOREIGN KEY(Id_adresse) REFERENCES adresse(Id_adresse),
   FOREIGN KEY(Id_app_user) REFERENCES app_user(Id_app_user)
);

CREATE TABLE article_commande(
   Id_article VARCHAR(255),
   Id_commande VARCHAR(255),
   quantite INT,
   total_price DECIMAL(15,2),
   is_deleted BOOLEAN,
   PRIMARY KEY(Id_article, Id_commande),
   FOREIGN KEY(Id_article) REFERENCES article(Id_article),
   FOREIGN KEY(Id_commande) REFERENCES commande(Id_commande)
);
