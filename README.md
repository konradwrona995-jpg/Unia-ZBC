# KS Unia Ząbkowice – strona (wersja GitHub Pages)

Statyczna wersja strony klubu. Pliki `*.html` w głównym folderze są **generowane automatycznie**, więc nie edytuj ich ręcznie.

## Jak to działa
- `_zrodla/` – źródła strony (PHP). GitHub Pages ich nie publikuje.
- `.github/workflows/odswiez-strone.yml` – automat, który co 3 godziny pobiera świeżą tabelę i terminarz z 90minut.pl i generuje strony od nowa. Uruchamia się też po każdej zmianie w `_zrodla/`.

## Zmiana treści (bez panelu admina)
| Co | Gdzie |
|---|---|
| Aktualności | `_zrodla/data/aktualnosci.php` |
| Kadra | `_zrodla/data/kadra.php` |
| Kontakt, trener, prezes, linki | `_zrodla/inc/config.php` |
| Zdjęcia | wgraj do `_zrodla/uploads/` i wpisz ścieżkę, np. `uploads/kadra/kowalski.jpg` |

Edytujesz plik na GitHubie (ikona ołówka), zapisujesz, a po 1–2 minutach strona się aktualizuje.

## Ustawienia repozytorium (jednorazowo)
1. **Settings → Pages** → Source: *Deploy from a branch*, gałąź `main`, folder `/ (root)`.
2. **Settings → Actions → General** → *Workflow permissions*: **Read and write permissions** → Save.
3. **Actions** → *Odśwież stronę* → **Run workflow** (pierwsze, testowe uruchomienie).

Pełna wersja z panelem admina wymaga hostingu z PHP. Wtedy wgrywa się zawartość `_zrodla/` razem z folderem `admin/` z projektu.
