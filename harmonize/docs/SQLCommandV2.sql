# USER
INSERT INTO user (username, password, email, description, profil, created_at, slug, roles, status)
VALUES 
('marc', '$argon2id$v=19$m=65536,t=4,p=1$PBnsNU/mVSgOeOHsEyN11w$WE9ckP8c8A8cXWUj3fQdKyIyFJEq/vDnl7ra5cyam10', 'marc@email.com', 'hello je suis marc chanteur non chalant mais dans un genre vieux beau je fais de la musique depuis diz ans', 1, NOW(), 'marc', '[]', 1),
('julie', '$argon2id$v=19$m=65536,t=4,p=1$K9lCOUqvD5J/w5f7oY0kEA$YAGxdQ+lI6QkOyikAUFxjHgbyqGIZ6KjX+cWjw5o89o', 'julie@email.com', 'hello bitch ! je suis Julie Spears!! so what !!', 1, NOW(), 'julie', '[]', 1),
('bob', '$argon2id$v=19$m=65536,t=4,p=1$CsHEM1l/H79u82MibBv3qw$Z38oA8aXEYvZrCexVb240EOtza4q9I2I/BGuuBg0k2k', 'bob@email.com', 'Bob say : smoke weed everyday like a lion in zion', 3, NOW(), 'bob', '[]', 1),
('vanessa', '$argon2id$v=19$m=65536,t=4,p=1$2P/dwtXNY6DAL1mBlaktVw$4LSBn9q9xOgpHT+gYch4bHtPJfDHUbC2foaWLj5kNCI', 'vanessa@email.com', 'Je suis Vanessa et je sui au paradis avec mon ami Lenny K', 2, NOW(), 'vanessa', '[]', 1),
('jason', '$argon2id$v=19$m=65536,t=4,p=1$SxMA8gq2TzOrLjhP3Mop6A$O2jNXeELQS0mMf/1Loui1e7TaNzPS0qAIBqjResGp5U', 'jason@email.com', 'Hello moi c''est Jason, je fais de la batterie depuis 5 ans et j''adore les prods bien lourdes. Je cherche principalement a collaborer avec des bassistes  ', 2, NOW(), 'jason', '[]', 1),
('maryline', '$argon2id$v=19$m=65536,t=4,p=1$5gJixZZaz1lBB3zT1iKE7g$l55QB9jSVZgCdJIj8m4ybpy5MnJ2hlTy84c2nsvyva4', 'maryline@email.com', 'Maryline 23 ans et chanteuse de jazz. Je sors du consrvatoire national et je cherche des gens avec qui collaborer mais seulment des personnes lectrices svp', 2, NOW(), 'maryline', '[]', 1),
('manu', '$argon2id$v=19$m=65536,t=4,p=1$uVZUO4SZ4yumFgoGFm0DcA$ySndbarDYetoFJ/hC2SyzrWzHlO/wkEfO0++og+toDw', 'manu@email.com', 'Moi c''est manu je suis compositeur guitariste chanteur. je suis fan de chanson francaise et je viens d''Amsterdam. Mon padre, Marin travaillait au port (d''Amsterdam)' , 2, NOW(), 'manu', '[]', 1),
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
(3, 4, 'C#9dim', 'Si vous avez six doigts pour jouer un accord de jazz hyper compliqué j''ai besoin de vous', 1, NOW(), 'c-9dim'),
(2, 7, 'Chanson francaise', 'Bonjour je cherche des musiciens pour faire en semble une superbe composition. Je cherche une bonne colaboration avant tout voici la piste de guitare que je propose', 1, NOW(), 'chanson-francaise'),
(3, 8, 'Jazz pour lecteurs', 'Je cherche des lecteurs pour composer ensemble ou reprendre des standards de jazz voici un petit apercu de ce que j''aime bien', 1, NOW(), 'jazz-pour-lecteurs'),
(3, 6, 'Reggae', 'Je cherche des personnes avec une bonne connaissance de la musique Reggae. Selon comment se deroule la collaboration nous pourrions faire mixer les titre', 1, NOW(), 'reggae'),
(6, 2, 'Pop electro', 'j''ai ete bien inspiree cette semaine je pense que ca peut vous donner un peu d''inspiration et pourquoi pas donner une superbe composition', 1, NOW(), 'pop-electro'),
(5, 1, 'Basse batterie', 'Avec un pote on a enregistre un basse batterie et ce serait super avec d''autres instruments. Pourquoi pas de la guitare du piano et de l''orgue dans un premier temps et le top serait de trouver un chanteur dans un second temps', 1, NOW(), 'basse-batterie')
;

# MESSAGE
INSERT INTO message (sender_id, recipient_id, title, message, is_read, created_at)
VALUES 
(1, 2, 'Je suis trop hypé par ce que tu fais', 'Ce que tu fais c\'est trop de la balle de fou furieux je veux en être', 0, NOW()),
(2, 1, 'Cimer frère', 'vas y rejoint le projet quand tu veux j\'attend avec impatience les sons dans l\'espace commentaire du projet ', 0, NOW()),
(3, 4, 'Smoke weed', 'J\'ai de la weed à vendre de la bonne de rastafari', 0, NOW()),
(3, 2, 'Smoke weed', 'J\'ai de la weed à vendre de la bonne de rastafari', 0, NOW()),
(4, 2, 'au paradis', 'On m\'a dit que tu faisais du super boom boom ! si mon projet te plait ajoute tes pistes audio dans les commentaires', 0, NOW()),
(2, 7, 'Une collaboration', 'je fais de la batterie si et je crois que l''on hqbite pas trop loin l''un de l''autre. Ce serait trop bien d''essayer de collaborer ensemble et pourquoi pas d''enregistrer en studio. A plus. Musicalement', 0, NOW()),
(7, 2, 'Lyon', 'Je vais hyper souvent au hotclub et au periscope a Lyon on s''y retrouve a l''occasion pour une jam session', 0, NOW()),
(8, 7, 'Guitare manouche', 'Hello j''ai ecoute tes compos et elles sont bien cools vraiment. Tu joues un peu de guitare jazz manouche par hasard ? je pense que cq pourrait bien coller avec mon projet si c''est le cas', 0, NOW())
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
(1, 4, 'Keep up the good work', NOW()),
(3, 6, 'Tellement bien WP', NOW()),
(5, 6, 'Ca m''inspire !! je prepare un truc un arrangement et je te l''envoie', NOW()),
(1, 6, 'genial bravo', NOW()),
(3, 7, 'Tellement bien WP', NOW()),
(5, 7, 'J''imagine trop une bonne melodie la dessus, je t''envoie ca demain', NOW()),
(3, 8, 'Tellement bien WP', NOW()),
(7, 8, 'Mortel (dans le bon sens du terme)', NOW()),
(7, 9, 'On en veut plus 30 secondes c''est vraiment trop court', NOW()),
(3, 9, 'Tellement bien WP', NOW())
;