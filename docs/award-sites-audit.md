---
title: "Audit ispirazione siti ristorante premiati"
type: audit
status: active
---

# Cosa vincono i siti ristorante premiati (audit)

Fonti: awwwards.com/websites/hotel-restaurant, elias.studio, gotenzo.com,
framer.com, getbento.com, magier.com, codedesign.ai, webflow.com/made-in-webflow,
hospitalitytech.com, hostinger.com, onepagelove.com, guide.michelin.com.

## Pattern ricorrenti nei siti che vincono

1. **Fotografia cibo protagonista** — hero e card con immagini reali di piatti,
   non illustrazioni o placeholder. Il cibo è il prodotto.
2. **Hero/editoriale tipografico** — serif display (Playfair-style) per il nome,
   eyebrow in maiuscolo spaziato, palette calda coerente con l'interior.
3. **CTA "Prenota" sempre visibile** — header sticky con bottone primario;
   il 65% dei clienti prenota dal sito, il bottone deve essere immediato.
4. **Menu come vetrina** — card prodotto con foto grande, prezzo, dettagli
   espandibili; filtri (allergeni/diete) come valore aggiunto EU 1169/2011.
5. **Micro-interazioni misurate** — hover lift, zoom immagine, transizioni
   brevi; `prefers-reduced-motion` rispettato.
6. **Compatta su mobile/tablet** — griglie fluide, touch target ≥44px,
   nav hamburger.
7. **Informazioni pratiche complete** — orari, contatti, indicazioni:
   il "minisito" deve rispondere a "dove sono, quando sono aperti, come prenoto".

## Applicato al tema Trattoria

- `.menu-hero` ora ha banda fotografica (immagine piatto + overlay terra) con
  testo chiaro — usata sia dalla home ristorante sia dalle pagine menu.
- `.dish` card: hover lift + zoom foto (`scale 1.06`), immagine 4:3 in alto,
  prezzo in chip, dettaglio espandibile con gallery.
- Header/footer restaurant = minisito (brand, nav contestuale, contatti footer).
- Orari di apertura e contatti dal profilo tenant (`GetRestaurantProfileAction`).
- Filtri allergeni/diete con icone SVG dedicate e legenda.
- Breakpoint: 1 col mobile / 2 da 640px / 3 da 1024px; nav mobile hamburger.

## Gap residui (backlog)

- Hero home con slideshow/foto ristorante (oggi immagine fissa del tema).
- Gallery dedicata piatti in homepage ("i più amati").
- Mappa embedded invece del link Google Maps.
- Testimonial/recensioni section.
- Animazioni scroll-reveal più ricche (oggi solo hero entrance).
