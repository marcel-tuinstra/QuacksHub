# QuacksHub

Backend API voor Quacks – een minimalistische, Symfony-gebaseerde projectmanagementtool met ondersteuning voor investment tracking. Ontworpen voor eenvoud, snelheid en uitbreidbaarheid via een gestructureerde API.

## Features

- REST API voor projecten, taken en gebruikers
- JWT-authenticatie via LexikJWTAuthenticationBundle
- Gebruikersregistratie en rolbeheer
- Validatie van input via Symfony Validator
- Ondersteuning voor relationele data (bijv. taken binnen projecten)
- (WIP) Investment tracking endpoints: assets, waardes, portfolio’s
- (WIP) Voorbereiding op financiële overzichten / grafieken

## Tech Stack

- **Framework**: Symfony 6
- **Language**: PHP 8+
- **Authenticatie**: LexikJWTAuthenticationBundle
- **Database**: PostgreSQL
- **ORM**: Doctrine
- **Validatie**: Symfony Validator component
- **Routing & Controllers**: Symfony Routing / Attributes
- **Dependency management**: Composer
- **Development tools**: Symfony CLI, dotenv, PHPUnit (indien aanwezig)
