# USER
INSERT INTO user (username, password, email, description, profil, created_at, slug, roles, status)
VALUES 
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