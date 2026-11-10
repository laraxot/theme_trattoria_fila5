# 📚 Trattoria Theme - Documentation Index

**Path**: `laravel/Themes/Trattoria/docs/`
**Tema**: @Themes/Trattoria

## 📄 Documenti

| File | Scopo |
|------|-------|
| [README.md](README.md) | Entrypoint docs tema, attivazione tenant, mappa cartelle |
| [theme.md](theme.md) | Palette, tipografia, classi CSS, layout e convenzioni routing Folio |
| [customer-flows-contract.md](customer-flows-contract.md) | Contratto pagine pubbliche (`/`, `/menu`, `/prenota`, `/ordine`) e campi form |
| [navigation-integration.md](navigation-integration.md) | Vincoli navigazione e label/icone Filament |

## BMAD + Second Brain

| Path | Scopo |
|------|-------|
| [bmad/](bmad/) | Filament blueprint tema e note BMAD |
| [bmad/filament-blueprint.md](bmad/filament-blueprint.md) | Confini XotBase/Filament, FO Folio, integrazione Restaurant |
| [stories/](stories/) | Story BMAD epic `E-TRATTORIA-1` |
| [../../../../docs/wiki/memories/restaurant-autocoscienza-tooling-and-docs.md](../../../../docs/wiki/memories/restaurant-autocoscienza-tooling-and-docs.md) | Memoria operativa progetto (tooling, env, log, symlink) |

## Stories

`stories/` contiene le story BMAD dell'epic `E-TRATTORIA-1` (foundation,
waiter ordering, customer menu/booking, order modes, quality gate). Vedi i
singoli file per stato, criteri di accettazione e tracciamento GitHub.

## 🔗 Riferimenti

- [Restaurant Module docs](../../Modules/Restaurant/docs/) — modelli, Actions e dominio consumati da questo tema (nessun indice unico: cercare per nome file)
- [Xot Module docs](../../Modules/Xot/docs/index.md) — `XotBaseServiceProvider` da cui estende `TrattoriaServiceProvider`
- [Root CLAUDE.md](../../CLAUDE.md) — stub on-demand del monorepo

---

**Ultimo aggiornamento**: 2026-11-10

> Punto di ingresso aggiornato: vedi [README.md](README.md). Questo indice resta per i link storici.
