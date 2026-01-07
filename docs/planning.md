# Planning (3 weken)

**Project:** Subspace — Social media platform  
**Datum:** 7 januari 2026  

Doel van deze planning: een haalbare verdeling van werk zodat de documentatie in week 1 af is en we in week 2–3 bouwen, testen en presenteren.

---

## Week 1 — Planning & ontwerp (oplevering documentatie vrijdag)
**Doelen:** scope afbakenen, ontwerp vastleggen, basis repo klaarzetten.

- Dag 1
  - Project scope: volgen/vrienden kiezen (we nemen “volgen” als default).
  - Rollen/taakverdeling + afspraken (Git flow, code review, naming conventions).
- Dag 2
  - Product backlog opstellen (MoSCoW) + acceptatiecriteria per Must.
- Dag 3
  - Wireframes: gebruikersdeel + admin (met user stories).
- Dag 4
  - Database ontwerp (ERD): tabellen + relaties + indexen.
- Dag 5 (vrijdag)
  - Documentatie opleveren en feedback verwerken.

Deliverables:
- [backlog.md](backlog.md)
- [wireframes.md](wireframes.md)
- [erd.md](erd.md)
- [projectboekje.md](projectboekje.md)

---

## Week 2 — Core development (MVP)
**Doelen:** werkende kernfunctionaliteit gebruikersdeel.

- Auth
  - Registratie + login/logout + sessies
- Profiel
  - Profielpagina (eigen + anderen) + basis profiel bewerken
- Posts
  - Post plaatsen (tekst) + feed overzicht
- Interactie
  - Like/unlike + comments toevoegen
- Security basis
  - Prepared statements, escaping, CSRF tokens, autorisatiechecks

Mijlpaal eind week 2:
- Demo: user kan registreren, posten, liken, commenten, profielen bekijken.

---

## Week 3 — Admin + polish
**Doelen:** adminomgeving + moderatie + afronding.

- Admin dashboard
  - Aantallen users/posts/comments/likes
- Moderatie
  - Posts verbergen/verwijderen
  - Reports bekijken (optioneel Must/Should afhankelijk van tijd)
- Blokkeren
  - Gebruiker blokkeren (login/actie beperken)
- UX & data
  - Bootstrap styling consistent
  - Vullen met testdata (meerdere users, serieus aantal posts/comments)
- Presentatie voorbereiding
  - Script/demo flow + samenwerking uitleg

Eindproduct:
- Gebruikersdeel + adminomgeving klaar voor demonstratie.
