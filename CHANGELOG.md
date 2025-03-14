# Changelog

Alle belangrijke wijzigingen aan dit project worden in dit bestand gedocumenteerd.

Het format is gebaseerd op [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
en dit project volgt [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2025-03-14

### Toegevoegd
- Streaming ondersteuning voor chat completions via `streamingChatCompletion` methode
- StreamingTrait voor herbruikbare streaming functionaliteit
- Voorbeeldcode voor streaming met OpenAI en DeepSeek
- Unit tests voor streaming functionaliteit

## [1.0.0] - 2025-03-14

### Toegevoegd
- Eerste officiële release
- Ondersteuning voor OpenAI API
- Ondersteuning voor DeepSeek API
- AIClientFactory voor het maken van provider-specifieke clients
- Gestandaardiseerde response formaat voor alle providers
- Uitgebreide test suite (unit, feature en integratie tests)
- GitHub Actions workflows voor CI/CD
- PHPStan voor statische code analyse

### Veiligheid
- Veilige omgang met API keys via dotenv voor tests
- API keys worden nooit in de code opgeslagen

## [Unreleased]

### Toegevoegd
- *Nog geen nieuwe features*

### Gewijzigd
- *Nog geen wijzigingen*

### Verwijderd
- *Nog niets verwijderd*

### Fixed
- *Nog geen fixes*

### Veiligheid
- *Nog geen veiligheidsverbeteringen*

[Unreleased]: https://github.com/OscarWeijman/AIClient/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/OscarWeijman/AIClient/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/OscarWeijman/AIClient/releases/tag/v1.0.0