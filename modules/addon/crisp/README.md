# Module Crisp — Sparklin

## Configuration

- **Website ID** : `326a0f31-24a5-4709-9538-ff5f4aa65f71`
- **Secret Key** : *(non configurée — optionnelle, pour vérification d'email)*

## Structure

```
modules/
└── addon/
    └── crisp/
        ├── crisp.php     # Config du module (WHMCS compatible)
        ├── hooks.php     # Hook PHP WHMCS (référence)
        └── README.md     # Ce fichier
```

## Intégration site statique

Le snippet Crisp est injecté directement avant `</body>` sur les **22 pages HTML** du site.

```html
<script type="text/javascript">
  window.$crisp=[];
  window.CRISP_WEBSITE_ID="326a0f31-24a5-4709-9538-ff5f4aa65f71";
  (function(){
    var d=document;
    var s=d.createElement("script");
    s.src="https://client.crisp.chat/l.js";
    s.async=1;
    d.getElementsByTagName("head")[0].appendChild(s);
  })();
</script>
```

## Fonctionnement

- **Widget chat** affiché sur toutes les pages du site
- **Asynchrone** : ne bloque pas le chargement des pages
- **Tableau de bord** : https://app.crisp.chat
- **Documentation** : https://docs.crisp.chat

## Pages exclues

- `/admin-blog/` — espace rédaction interne (non public)
