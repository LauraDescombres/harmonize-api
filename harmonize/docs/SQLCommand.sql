# USER
INSERT INTO user (username, password, email, description, profil, created_at, slug, roles, status)
VALUES 
('marc', '$argon2id$v=19$m=65536,t=4,p=1$PBnsNU/mVSgOeOHsEyN11w$WE9ckP8c8A8cXWUj3fQdKyIyFJEq/vDnl7ra5cyam10', 'marc@email.com', 'hello je suis marc chanteur non chalant mais dans un genre vieux beau', 1, NOW(), 'marc', '[]', 1),
('julie', '$argon2id$v=19$m=65536,t=4,p=1$K9lCOUqvD5J/w5f7oY0kEA$YAGxdQ+lI6QkOyikAUFxjHgbyqGIZ6KjX+cWjw5o89o', 'julie@email.com', 'hello bitch ! je suis Julie Spears!! so what !!', 1, NOW(), 'julie', '[]', 1),
('bob', '$argon2id$v=19$m=65536,t=4,p=1$CsHEM1l/H79u82MibBv3qw$Z38oA8aXEYvZrCexVb240EOtza4q9I2I/BGuuBg0k2k', 'bob@email.com', 'Bob say : smoke weed everyday like a lion in zion', 3, NOW(), 'bob', '[]', 1),
('vanessa', '$argon2id$v=19$m=65536,t=4,p=1$2P/dwtXNY6DAL1mBlaktVw$4LSBn9q9xOgpHT+gYch4bHtPJfDHUbC2foaWLj5kNCI', 'vanessa@email.com', 'Je suis Vanessa et je sui au paradis avec mon ami Lenny K', 2, NOW(), 'vanessa', '[]', 1),
('superharmonize', '$argon2id$v=19$m=65536,t=4,p=1$I0mNZ3KRqaNYoTBIwMfsKw$5CP/a5H+zVDgauTHy29pEyHdGErMASFtSSxuwlR1vPs', 'peteralexandremusic@gmail.com', 'Wesh C nous les boss alors pousse toi de devant Mark Zuckerberg', 1, NOW(), 'superharmonize', '["ROLE_SUPER_ADMIN"]', 1),
('harmonize', '$argon2id$v=19$m=65536,t=4,p=1$Fl9h5ryjPOYxSpDFp0RXtA$4fuNFVjBedyFm044kwyQBKR9SsFPqb2B2q/Es0IPWT0', 'harmonize@gmail.com', 'Accès au back office mais pas tout', 1, NOW(), 'harmonize', '["ROLE_ADMIN"]', 1)
;

# MUSIC_GENRE
INSERT INTO music_genre (name)
VALUES
('Non défini'),
('Pop/Rock/Metal'),
('Indie/Folk/Acoustic'),
('Jazz/Musique du monde/Classique'),
('Electronique/EDM/Experimentale'),
('Rap/Musiques Urbaines/Hip-Hop'),
('Autre/Bizarre/Innatendu')
;

# PROJECT
INSERT INTO project (music_genre_id, user_id, name, description, status, created_at, slug)
VALUES 
(2, 1, 'Viens faire poum tchak', 'Viens faire de la batterie sur ma nouvelle composition ! Un gros boum boum avec des pshtt pshtt et takatak', 1, NOW(), 'viens-faire-poum-tchak'),
(5, 2, 'Wesh les gros', 'Wesh g un gro son si tu veu posé ton flow dessus asy viens', 1, NOW(), 'wesh-les-gros'),
(4, 3, 'Mix Hardcore', 'Un son du turfu posé sous acide un Vendredi soir ou un Samedi matin très tôt', 1, NOW(), 'mix-hardcore'),
(3, 4, 'C#9dim', 'Si vous avez six doigts pour jouer un accord de jazz hyper compliqué j\'ai besoin de vous', 1, NOW(), 'c-9dim')
;

# MESSAGE
INSERT INTO message (sender_id, recipient_id, title, message, is_read, created_at)
VALUES 
(1, 2, 'Je suis trop hypé par ce que tu fais', 'Ce que tu fais c\'est trop de la balle de fou furieux je veux en être', 0, NOW()),
(2, 1, 'Cimer frère', 'vas y rejoint le projet quand tu veux j\'attend avec impatience les sons dans l\'espace commentaire du projet ', 0, NOW()),
(3, 4, 'Smoke weed', 'J\'ai de la weed à vendre de la bonne de rastafari', 0, NOW()),
(3, 2, 'Smoke weed', 'J\'ai de la weed à vendre de la bonne de rastafari', 0, NOW()),
(4, 2, 'au paradis', 'On m\'a dit que tu faisais du super boom boom ! si mon projet te plait ajoute tes pistes audio dans les commentaires', 0, NOW())
;

# COMMENT
INSERT INTO comment (user_id, project_id, description, created_at)
VALUES 
(2, 1, 'Pouah mais c\'est tellement mortel', NOW()),
(1, 1, 'Venez Venez on va bien s\'amuser', NOW()),
(4, 1, 'J\'aurais trop besoin de gens comme vous pour mon projet', NOW()),
(2, 3, 'Wééééééééééééééééééééé', NOW()),
(1, 3, 'CE beat a fait littéralement gonfler ma ....', NOW()),
(3, 4, 'Tellement bien WP', NOW()),
(1, 4, 'Keep up the good work', NOW())
;