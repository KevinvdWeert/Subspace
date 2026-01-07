# Database ontwerp (ERD-uitwerking)

Doel: een relationele MySQL database die users, posts en interacties ondersteunt (likes/comments) + admin moderatie.

> Tip: teken dit in MySQL Workbench als ERD en exporteer een screenshot/PDF voor de officiële inlevering.

---

## Overzicht tabellen
- `users`
- `profiles`
- `posts`
- `post_likes`
- `post_comments`
- `follows` (volgen) *(Should, maar DB kan alvast klaar staan)*
- `user_blocks` (admin blokkeren)
- `post_reports` *(Should)*

---

## Tabellen (kolommen)

### 1) `users`
- `id` (PK, INT, AI)
- `username` (VARCHAR, UNIQUE)
- `email` (VARCHAR, UNIQUE)
- `password_hash` (VARCHAR)
- `role` (ENUM('user','admin') of VARCHAR)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

**Indexen:** UNIQUE(username), UNIQUE(email)

---

### 2) `profiles`
- `user_id` (PK + FK → users.id)
- `display_name` (VARCHAR, NULL)
- `bio` (TEXT, NULL)
- `avatar_url` (VARCHAR, NULL)
- `updated_at` (DATETIME)

**Relatie:** 1–1 met users.

---

### 3) `posts`
- `id` (PK, INT, AI)
- `user_id` (FK → users.id)
- `content` (TEXT)
- `link_url` (VARCHAR, NULL) *(optioneel)*
- `media_url` (VARCHAR, NULL) *(optioneel)*
- `is_hidden` (TINYINT(1), default 0) *(moderatie / soft hide)*
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

**Indexen:** (user_id, created_at), (created_at)

---

### 4) `post_likes`
- `post_id` (FK → posts.id)
- `user_id` (FK → users.id)
- `created_at` (DATETIME)

**PK:** (post_id, user_id)  
**Regel:** één like per user per post.

---

### 5) `post_comments`
- `id` (PK, INT, AI)
- `post_id` (FK → posts.id)
- `user_id` (FK → users.id)
- `content` (TEXT)
- `is_hidden` (TINYINT(1), default 0) *(optioneel moderatie)*
- `created_at` (DATETIME)

**Indexen:** (post_id, created_at), (user_id)

---

### 6) `follows` *(Should)*
- `follower_user_id` (FK → users.id)
- `followed_user_id` (FK → users.id)
- `created_at` (DATETIME)

**PK:** (follower_user_id, followed_user_id)

---

### 7) `user_blocks`
- `id` (PK, INT, AI)
- `user_id` (FK → users.id)
- `blocked_by_admin_id` (FK → users.id) *(admin die blokkeert)*
- `reason` (VARCHAR, NULL)
- `blocked_until` (DATETIME, NULL) *(NULL = permanent)*
- `created_at` (DATETIME)

**Regel:** tijdens blokkade: user kan niet posten/liken/commenten (minimaal) en/of niet inloggen.

---

### 8) `post_reports` *(Should)*
- `id` (PK, INT, AI)
- `post_id` (FK → posts.id)
- `reported_by_user_id` (FK → users.id)
- `reason` (VARCHAR)
- `status` (ENUM('open','reviewed','dismissed') of VARCHAR)
- `created_at` (DATETIME)

---

## Relaties (samenvatting)
- users 1—1 profiles
- users 1—N posts
- posts 1—N post_comments
- users N—N posts via post_likes
- users N—N users via follows
- users 1—N user_blocks (admin actie op user)

## Aanbevolen referentiële regels
- `posts.user_id` ON DELETE CASCADE (of RESTRICT als je history wil bewaren)
- likes/comments ON DELETE CASCADE bij post delete
- profiles ON DELETE CASCADE bij user delete
