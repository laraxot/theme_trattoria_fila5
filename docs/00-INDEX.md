# 📚 Trattoria Theme - Documentation Index

**Path**: `laravel/Themes/Trattoria/docs/`
**Tema**: @Themes/Trattoria

## 📄 Documenti

| File | Scopo |
|------|-------|
| [theme.md](theme.md) | Palette colori, tipografia, classi CSS e layout realmente implementati |
| [customer-flows-contract.md](customer-flows-contract.md) | Contratto delle 4 pagine pubbliche (`/`, `/menu`, `/prenota`, `/ordine`) e dei campi form collegati ai model Restaurant |
| [navigation-integration.md](navigation-integration.md) | Vincoli per l'integrazione della navigazione con le label/icone fornite da Filament |

## Stories

`stories/` contiene le story BMAD dell'epic `E-TRATTORIA-1` (foundation,
waiter ordering, customer menu/booking, order modes, quality gate). Vedi i
singoli file per stato, criteri di accettazione e tracciamento GitHub.

## 🔗 Riferimenti

- [Restaurant Module docs](../../Modules/Restaurant/docs/) — modelli, Actions e dominio consumati da questo tema (nessun indice unico: cercare per nome file)
- [Xot Module docs](../../Modules/Xot/docs/index.md) — `XotBaseServiceProvider` da cui estende `TrattoriaServiceProvider`
- [Root CLAUDE.md](../../CLAUDE.md) — stub on-demand del monorepo

---

**Ultimo aggiornamento**: 2026-09-17
