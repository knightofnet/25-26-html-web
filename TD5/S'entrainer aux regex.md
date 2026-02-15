# S’entraîner aux regex

> **Mode d’emploi (regex101)**
>
> URL : https://regex101.com/
>
> 1. Écrivez votre regex dans le champ du haut.
> 2. Collez dans le champ du bas des lignes de test (positives et négatives).
> 3. Objectif : **seules** les lignes positives doivent être surlignées **entièrement**.
>    👉 Utilisez souvent `^` et `$` pour forcer le match sur toute la ligne.

---

## Exercice 1 — Code produit “FAC-2026-XXX”

**Règle :** commence par `FAC-`, puis `2026`, puis `-`, puis **3 lettres majuscules**.

**✅ Doit matcher :**

* `FAC-2026-ABC`
* `FAC-2026-ZZZ`
* `FAC-2026-MTH`

**❌ Ne doit pas matcher :**

* `FAC-2026-AB`
* `FAC-2026-ABCD`
* `fac-2026-ABC`
* `FAC-2025-ABC`

---

## Exercice 2 — ID étudiant

**Contexte :** les IDs sont au format `L1-` suivi de **6 chiffres** (les zéros au début sont autorisés).

**✅ Doit matcher :**

* `L1-000012`
* `L1-123456`
* `L1-042001`

**❌ Ne doit pas matcher :**

* `L1-12345`
* `L1-1234567`
* `L2-123456`
* `L1-12A456`

---

## Exercice 3 — Salle de cours

**Règle :** une lettre de bâtiment **A à D**, un tiret `-`, puis **3 chiffres**.

**✅ Doit matcher :**

* `A-101`
* `D-004`
* `B-999`

**❌ Ne doit pas matcher :**

* `E-101`
* `A101`
* `A-10`
* `A-10B`

---

## Exercice 4 — Code promo simple

**Règle :** **2 lettres majuscules**, puis `-`, puis **4 chiffres**.

**✅ Doit matcher :**

* `AB-1234`
* `ZZ-0000`
* `PC-9876`

**❌ Ne doit pas matcher :**

* `ab-1234`
* `ABC-1234`
* `AB-123`
* `AB-12C4`

---

## Exercice 5 — Référence matériel “UT”

**Règle :** commence par `UT`, puis **3 chiffres**, puis **2 lettres majuscules**.

**✅ Doit matcher :**

* `UT123AB`
* `UT000ZZ`
* `UT987PC`

**❌ Ne doit pas matcher :**

* `ut123AB`
* `UT12AB`
* `UT123Ab`
* `UT1234AB`

---

## Exercice 6 — Couleur hexadécimale (HTML)

**Règle :** commence par `#`, puis **6 caractères hexadécimaux** (0–9 ou A–F en majuscules).

**✅ Doit matcher :**

* `#00FFAA`
* `#ABCDEF`
* `#1234AF`

**❌ Ne doit pas matcher :**

* `00FFAA`
* `#00FFA`
* `#00FFAA11`
* `#00ffAA`
* `#GGHHII`

---

## Exercice 7 — Nom de fichier PDF “cours_…”

**Règle :** commence par `cours_`, puis **au moins 1 lettre minuscule**, puis `.pdf`.

**✅ Doit matcher :**

* `cours_regex.pdf`
* `cours_math.pdf`
* `cours_a.pdf`

**❌ Ne doit pas matcher :**

* `cours_.pdf`
* `cours_Regex.pdf`
* `cours_regex.PDF`
* `moncours_regex.pdf`

---

## Exercice 8 — Code “TPx” (x entre 1 et 9)

**Règle :** `TP` suivi d’**un chiffre entre 1 et 9**.

**✅ Doit matcher :**

* `TP1`
* `TP7`
* `TP9`

**❌ Ne doit pas matcher :**

* `TP0`
* `TP10`
* `tp1`
* `TPa`

---

## Exercice 9 — Ligne CSV (3 entiers)

**Règle :** exactement **3 entiers** (1 ou plusieurs chiffres) séparés par des `;`.

**✅ Doit matcher :**

* `12;0;345`
* `1;2;3`
* `000;42;9`

**❌ Ne doit pas matcher :**

* `12;0`
* `12;0;345;7`
* `12; ;345`
* `12,a;0;345`

---

## Exercice 10 — Code “VILLE:xxxxx”

**Règle :** commence par `VILLE:`, puis **5 chiffres**.

**✅ Doit matcher :**

* `VILLE:37000`
* `VILLE:75001`
* `VILLE:00000`

**❌ Ne doit pas matcher :**

* `VILLE:3700`
* `ville:37000`
* `VILLE:37A00`
* `XVILLE:37000`

---

## Exercice 11 — Nom “Prenom Nom” (simple)

**Règle :** un **mot** (lettres uniquement) + **un espace** + un **mot** (lettres uniquement).
*(Pas d’accents, pas de tirets, juste A–Z/a–z.)*

**✅ Doit matcher :**

* `Alice Martin`
* `Bob Dupont`
* `Chloe Bernard`

**❌ Ne doit pas matcher :**

* `AliceMartin`
* `Alice  Martin` (deux espaces)
* `Alice MARTIN` (si tu imposes une casse précise)
* `Alice M4rtin`

---

## Exercice 12 — Téléphone français (10 chiffres)

**Règle :** 10 chiffres, le premier est `0`, le second est entre `1` et `9`, puis 8 chiffres.

**✅ Doit matcher :**

* `0123456789`
* `0699988776`
* `0102030405`

**❌ Ne doit pas matcher :**

* `0023456789`
* `012345678`
* `01234567890`
* `01 23 45 67 89` (ici, interdit : espaces)

---

## Exercice 13 — Téléphone français en blocs “01 23 45 67 89”

**Règle :** format **5 blocs de 2 chiffres** séparés par **un espace** :

* le premier bloc commence par `0` puis `[1-9]`
* puis 4 blocs “ espace + 2 chiffres ”

**✅ Doit matcher :**

* `01 23 45 67 89`
* `06 99 88 77 66`
* `09 10 20 30 40`

**❌ Ne doit pas matcher :**

* `0123456789`
* `01 234 56 78 90`
* `00 12 34 56 78`
* `01-23-45-67-89`

---

## Exercice 14 — Téléphone FR “0…” OU “+33 …”

**Règle :** accepte **soit** :

* `0` + (chiffre 1 à 9) + (8 chiffres), **sans espaces**
  **soit**
* `+33` + **un espace** + (chiffre 1 à 9) + 4 blocs “(espace optionnel) + 2 chiffres”
  *(Oui, c’est un peu plus technique : parenthèses + `|` + échappement du `+`.)*

**✅ Doit matcher :**

* `0123456789`
* `+33 1 23 45 67 89`
* `+33 6 99887766` *(si tu autorises les espaces “parfois”)*

**❌ Ne doit pas matcher :**

* `+33 0 23 45 67 89`
* `+33 6-99-88-77-66`
* `0033 6 99 88 77 66`

---

## Exercice 15 — URL simple (http/https)

**Règle :**

* commence par `http://` ou `https://`
* puis un domaine composé de lettres/chiffres/`.` `_` `-`
* puis un point `.`
* puis une extension de **2 à 4 lettres**
* puis un chemin optionnel qui commence par `/` et ne contient **pas d’espace**

**✅ Doit matcher :**

* `http://example.com`
* `https://sub.domain-fr.org/path`
* `https://a_b-c.fr/abc/def`

**❌ Ne doit pas matcher :**

* `ftp://example.com`
* `https://example`
* `https://example.c`
* `https://example.comm`
* `https://example.com/avec espace`

---

## Exercice 16 — URL (http/https/ftp)

**Règle :** même idée que l’exercice 15, mais le protocole peut être `http`, `https` **ou** `ftp`.

**✅ Doit matcher :**

* `ftp://example.com`
* `http://site.net`
* `https://a.fr/x`

**❌ Ne doit pas matcher :**

* `file://example.com`
* `ftps://example.com`
* `ftp:/example.com`
* `ftp://example.123`

---

## Exercice 17 — Email (version “cours” simplifiée)

**Règle (simple) :**

* une partie “login” avec lettres/chiffres/`.` `_` `-` (au moins 1)
* puis `@`
* puis un domaine (lettres/chiffres/`.` `-`) (au moins 1)
* puis `.` + extension de **2 à 4 lettres**

**✅ Doit matcher :**

* `alice@univ.fr`
* `bob.dupont-42@site.com`
* `a_b@sub.domain.net`

**❌ Ne doit pas matcher :**

* `alice@univ`
* `@univ.fr`
* `alice@.fr`
* `alice univ.fr`
* `alice@univ.c`

---

## Exercice 18 — Adresse IPv4 (syntaxe uniquement)

**Règle :** 4 groupes de **1 à 3 chiffres** séparés par des points.
*(On ne vérifie pas ici “0–255”, seulement la forme.)*

**✅ Doit matcher :**

* `127.0.0.1`
* `192.168.1.10`
* `8.8.8.8`

**❌ Ne doit pas matcher :**

* `127.0.0`
* `127.0.0.1.5`
* `127,0,0,1`
* `abc.def.ghi.jkl`

---

## Exercice 19 — Log HTTP (GET/POST)

**Règle :**

* commence par `GET` ou `POST`
* puis un espace
* puis un chemin qui commence par `/` et n’a **pas d’espace**
* puis un espace
* puis `HTTP/1.0` ou `HTTP/1.1`

**✅ Doit matcher :**

* `GET /index.html HTTP/1.1`
* `POST /api/users HTTP/1.0`
* `GET /a/b/c?x=1 HTTP/1.1`

**❌ Ne doit pas matcher :**

* `PUT /index.html HTTP/1.1`
* `GET index.html HTTP/1.1` (manque le `/`)
* `GET /index.html HTTP/2.0`
* `GET /avec espace HTTP/1.1`

---

## Exercice 20 — Mot de passe (avec lookahead, niveau “débutant++”)

**Règle :**

* au moins **8 caractères**
* uniquement lettres et chiffres
* contient au moins **1 majuscule**
* contient au moins **1 minuscule**
* contient au moins **1 chiffre**

**✅ Doit matcher :**

* `Abcdefg1`
* `A1bcdefg`
* `PassWord9`

**❌ Ne doit pas matcher :**

* `abcdefg1` (pas de majuscule)
* `ABCDEFG1` (pas de minuscule)
* `Abcdefgh` (pas de chiffre)
* `Abc1!def` (caractère interdit)
