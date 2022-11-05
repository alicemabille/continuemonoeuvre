DROP TABLE IF EXISTS ecrire;
DROP TABLE IF EXISTS reagir; 
DROP TABLE IF EXISTS contenir;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS texte;
DROP TABLE IF EXISTS theme;

CREATE TABLE texte(
    id_texte CHAR(5),
    titre_texte VARCHAR(100),
    contenu_texte VARCHAR(150),
    CONSTRAINT texte_pk PRIMARY KEY (id_texte)
);
CREATE TABLE theme(
    theme VARCHAR(50),
    CONSTRAINT theme_pk PRIMARY KEY (theme)
);
CREATE TABLE utilisateur(
    nom_utilisateur VARCHAR(15),
    mail_utilisateur VARCHAR(50),
    num_tel_utilisateur CHAR(10),
    naissance_utilisateur DATE,
    mdp_chiff_utilisateur VARCHAR(100),
    cle_verification_utilisateur CHAR(32),
    compte_actif_utilisateur BOOLEAN DEFAULT false;
    CONSTRAINT utilisateur_pk PRIMARY KEY (nom_utilisateur)
);
CREATE TABLE ecrire(
    nom_auteur VARCHAR(15),
    id_ecrit CHAR(5),
    date_ecrit DATE,
    CONSTRAINT ecrire_pk PRIMARY KEY (nom_auteur, id_ecrit),
    CONSTRAINT ecrire_nom_auteur_fk FOREIGN KEY (nom_auteur) REFERENCES utilisateur (nom_utilisateur),
    CONSTRAINT ecrire_id_ecrit_fk FOREIGN KEY (id_ecrit) REFERENCES texte (id_texte)
);
CREATE TABLE reagir(
    nom_auteur_reaction VARCHAR(15),
    id_texte_reaction CHAR(5),
    url_reaction VARCHAR(100),
    CONSTRAINT reagir_pk PRIMARY KEY (nom_auteur_reaction, id_texte_reaction),
    CONSTRAINT reagir_nom_auteur_reaction_fk FOREIGN KEY (nom_auteur_reaction) REFERENCES utilisateur (nom_utilisateur),
    CONSTRAINT reagir_id_texte_reaction_fk FOREIGN KEY (id_texte_reaction) REFERENCES texte (id_texte)
);
CREATE TABLE contenir(
    id_texte_contenu CHAR(5),
    id_theme_contenu CHAR(5),
    CONSTRAINT contenir_pk PRIMARY KEY (id_texte_contenu, id_theme_contenu),
    CONSTRAINT id_texte_contenu_fk FOREIGN KEY (id_texte_contenu) REFERENCES texte (id_texte),
    CONSTRAINT id_theme_contenu_fk FOREIGN KEY (id_theme_contenu) REFERENCES theme (theme)
);
