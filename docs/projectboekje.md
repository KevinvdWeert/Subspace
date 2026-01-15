# Projectboekje — Social Media (Development – 2.2.W08)

**Projectnaam:** Subspace (social media platform)

**Leerjaar:** 2  
**Periode:** 3 weken  
**Datum:** 7 januari 2026  
**Teamleden:** Kevin, kijan, luka

---

## Inhoud
1. [Project](#project)
2. [Doel](#doel)
3. [Omschrijving](#omschrijving)
4. [Functionaliteiten](#functionaliteiten)
5. [Planning en documentatie](#planning-en-documentatie)
6. [Aandachtspunten (kwaliteit & security)](#aandachtspunten-kwaliteit--security)
7. [Producten / oplevering](#producten--oplevering)
8. [Beoordeling (koppeling aan criteria)](#beoordeling-koppeling-aan-criteria)
9. [Evaluatievragen](#evaluatievragen)

---

## Project
Dit project wordt uitgevoerd door **2 of 3 personen** met een doorlooptijd van **3 weken**. Het eindproduct is een eigen social media platform met zowel een **gebruikersdeel** als een **adminomgeving**.

## Doel
Binnen 3 weken leveren we een geavanceerde webapplicatie op die laat zien dat we:
- Gestructureerde PHP-code kunnen schrijven (herbruikbaar, overzichtelijk).
- Een **actueel CSS framework** gebruiken (Bootstrap).
- Bewust omgaan met **beveiliging** (privacy, datalekken, veilige opslag/verwerking).

## Omschrijving
Social media-platforms hebben de afgelopen jaren te maken gehad met privacyproblemen en datalekken. De vraag naar **transparante, veilige en gebruiksvriendelijke** alternatieven groeit.

We ontwerpen en bouwen daarom een eigen platform waarbij we volledige controle houden over:
- data (gebruikers, posts, interacties)
- functionaliteit (feed, profielen, likes, comments)
- beveiliging (authenticatie, autorisatie, input-validatie)

Kern van het platform:
- Gebruikers kunnen posten (minimaal tekst; uitbreidbaar met links/afbeeldingen/video)
- Interactie via likes en comments
- Netwerkfunctie via volgen/vrienden/groepen

## Functionaliteiten
### Gebruikersdeel (minimaal)
- Registreren en inloggen.
- Eigen profiel bekijken en aanpassen.
- Profielen van andere gebruikers bekijken.
- Contact maken (volgen/vrienden) en een overzicht daarvan.
- Posts plaatsen (tekst; optioneel media/link).
- Posts liken.
- Comments plaatsen onder posts.

### Adminomgeving (minimaal)
- Dashboard met informatie over gebruik/aantallen (users, posts, comments, likes).
- Moderatie: posts kunnen verbergen/verwijderen, reports bekijken.
- Gebruikers blokkeren (tijdelijk/permanent).

## Planning en documentatie
De aanpak volgt de eis dat **week 1** in het teken staat van **planning en ontwerp**.

Documentatie die we vóór het bouwen opleveren:
- Product backlog (MoSCoW) en sprint-/weekplanning.
- Wireframes (gebruikersdeel + admin) met user stories.
- Database ontwerp (ERD) met tabellen, kolommen en relaties.

Koppelingen naar de uitwerkingen:
- Planning: [planning.md](planning.md)
- Database/ERD: [erd.md](erd.md)

## Aandachtspunten (kwaliteit & security)
### Codekwaliteit
- Duidelijke naamgeving, kleine functies, herbruikbare include-structuur.
- Scheiding van concerns: database-laag, auth, views/templates.
- Nuttig commentaar waar het ontwerp/keuze toelicht (niet “wat de code al zegt”).

### Security (minimale baseline)
- Wachtwoorden opslaan met `password_hash()` en controleren met `password_verify()`.
- Prepared statements (PDO) voor alle database queries (SQL-injectie voorkomen).
- Output escapen in HTML (XSS beperken), bijv. `htmlspecialchars()`.
- CSRF-bescherming op POST-formulieren (token per sessie).
- Session hardening (cookie flags, session regeneration na login).
- Autorisatiechecks (user vs admin; eigenaar van post/comment).

## Producten / oplevering
We leveren op:
- Projectplanning
- Product backlog (MoSCoW)
- Wireframes + beschrijving
- ERD (MySQL Workbench) + tabelbeschrijving
- Werkende gebruikerswebsite met aantrekkelijke interface
- Meerdere users + serieus aantal posts/comments (seed/handmatig vullen)
- Adminomgeving

## Beoordeling (koppeling aan criteria)
### B1-K1-W1 — Plant werkzaamheden en bewaakt voortgang
- Voorblad + inhoudsopgave aanwezig.
- Realistische planning met mijlpalen.
- Week 1 documentatie opgeleverd.

### B1-K1-W2 — Ontwerpt software
- Backlog (MoSCoW) met prioriteiten.
- Wireframes gebruikersdeel + admindeel met user stories.
- Database ontwerp (ERD + relaties).

### B1-K1-W3 — Realiseert (onderdelen van) software
- Overzichtelijke code (structuur, includes, database helper/klasse).
- Voldoende testdata (users/posts/comments) om functies te demonstreren.