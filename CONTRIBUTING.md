# Bijdragen aan AI Client

Bedankt voor je interesse in het bijdragen aan AI Client! We waarderen alle hulp, of het nu gaat om het melden van bugs, het voorstellen van nieuwe features, of het bijdragen van code.

## Code of Conduct

Dit project en iedereen die eraan deelneemt, wordt verwacht zich te houden aan een respectvolle en inclusieve omgeving. Wees vriendelijk en open voor ideeën van anderen.

## Hoe kan ik bijdragen?

### Bugs melden

Als je een bug vindt, open dan een issue op GitHub met de volgende informatie:

- Een duidelijke titel en beschrijving van het probleem
- Stappen om het probleem te reproduceren
- Verwacht gedrag vs. daadwerkelijk gedrag
- PHP versie en andere relevante omgevingsinformatie

### Features voorstellen

We staan open voor nieuwe ideeën! Open een issue op GitHub met:

- Een duidelijke titel en beschrijving van de feature
- Uitleg waarom deze feature nuttig zou zijn
- Eventuele voorbeelden van hoe de feature zou werken

### Pull Requests

1. Fork de repository
2. Maak een nieuwe branch vanaf `main`
3. Voeg je wijzigingen toe
4. Zorg ervoor dat alle tests slagen
5. Update de documentatie indien nodig
6. Update CHANGELOG.md onder de [Unreleased] sectie
7. Dien een pull request in

## Ontwikkelingsproces

### Lokale ontwikkelomgeving opzetten

```bash
# Clone je fork
git clone https://github.com/jouw-username/AIClient.git
cd AIClient

# Installeer dependencies
composer install

# Kopieer .env.example naar .env voor tests
cp .env.example .env
```

### Tests uitvoeren

```bash
# Unit en feature tests
composer test

# Alleen unit tests
composer test-unit

# Alleen feature tests
composer test-feature

# Integratie tests (vereist API keys in .env)
composer test-integration

# Alle tests
composer test-all
```

### Code kwaliteit

We gebruiken PHPStan voor statische code analyse:

```bash
composer analyse
```

## Versioning

Dit project volgt [Semantic Versioning](https://semver.org/). In het kort:

- MAJOR versie wanneer je incompatibele API wijzigingen maakt
- MINOR versie wanneer je functionaliteit toevoegt op een backwards-compatible manier
- PATCH versie wanneer je backwards-compatible bug fixes maakt

## Release Proces

1. Update CHANGELOG.md
2. Verhoog de versie in relevante bestanden
3. Commit de wijzigingen
4. Tag de commit met de nieuwe versie (bijv. `v1.1.0`)
5. Push de tag naar GitHub

## Vragen?

Heb je vragen over het bijdragen? Open een issue op GitHub en we helpen je graag verder.