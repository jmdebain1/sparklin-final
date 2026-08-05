#!/usr/bin/env bash
# ══════════════════════════════════════════════════════════════════════════
#  build-static.sh — pré-rend le site PHP en HTML statique pour Netlify
#
#  Principe : les 30 pages PHP (27 publiques + 404 + 2 admin-blog) ne
#  tournent jamais côté client sur Netlify. On les exécute UNE FOIS, ici,
#  pendant le build (via le serveur intégré PHP), pour chacune des 7 langues
#  publiques, et on écrit le HTML obtenu sur disque. Netlify ne sert ensuite
#  que ce dossier dist/ — zéro PHP au runtime.
#
#  Schéma d'URL préservé : la version par défaut (fr) est écrite au chemin
#  naturel (ex. dist/spark-pilot/index.html → /spark-pilot/). Les 7 langues
#  sont AUSSI écrites sous dist/_i18n/<lang>/... — invisibles publiquement,
#  seulement utilisées par une règle de réécriture Netlify (?lang=xx),
#  ce qui permet de garder EXACTEMENT les URLs ?lang= déjà en place
#  (hreflang/canonical/sitemap construits dessus, aucune reprise nécessaire).
# ══════════════════════════════════════════════════════════════════════════
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DIST="$ROOT/dist"
PORT=8099
HOST="127.0.0.1"
LANGS=(fr en de es th ms id)

# Pages publiques : chemin relatif (avec slash final) tel qu'exposé en prod.
PAGES=(
  "/"
  "/a-propos/"
  "/app/"
  "/blog/"
  "/blog/gireve-interoperabilite-bornes/"
  "/blog/irve-reglementation-lom-2024/"
  "/blog/load-balancing-recharge-electrique/"
  "/blog/remboursement-recharge-domicile-urssaf/"
  "/cas/camping/"
  "/cas/collaborateurs/"
  "/cas/collectivite/"
  "/cas/hotel/"
  "/cas/pme/"
  "/cgu/"
  "/cgu-app/"
  "/contact/"
  "/evenements/"
  "/livre-blanc/"
  "/livre-blanc/merci/"
  "/mentions-legales/"
  "/politique-confidentialite/"
  "/politique-cookies/"
  "/spark-1/"
  "/spark-go-e/"
  "/spark-pilot/"
  "/spark-plus/"
  "/support/"
)

echo "── build-static: nettoyage ─────────────────────────────────"
rm -rf "$DIST"
mkdir -p "$DIST" "$DIST/_i18n"

echo "── build-static: démarrage du serveur PHP (build only) ─────"
export SK_STATIC_BUILD=1
php -S "$HOST:$PORT" -t "$ROOT" >"$ROOT/.build-php-server.log" 2>&1 &
PHP_PID=$!
trap 'kill $PHP_PID 2>/dev/null || true' EXIT

# attendre que le serveur réponde
for i in $(seq 1 30); do
  if curl -s -o /dev/null "http://$HOST:$PORT/"; then break; fi
  sleep 0.3
done

fetch() {
  # fetch <url> <outfile> [code_attendu=200]
  local url="$1" out="$2" expected="${3:-200}"
  mkdir -p "$(dirname "$out")"
  local code
  code=$(curl -s -o "$out" -w "%{http_code}" "$url")
  if [ "$code" != "$expected" ]; then
    echo "  ⚠️  HTTP $code (attendu $expected) sur $url" >&2
    return 1
  fi
}

echo "── build-static: rendu des pages publiques (${#PAGES[@]} pages × ${#LANGS[@]} langues) ──"
fail=0
for page in "${PAGES[@]}"; do
  # chemin de sortie par défaut (fr, URL propre)
  if [ "$page" = "/" ]; then
    default_out="$DIST/index.html"
  else
    default_out="$DIST${page}index.html"
  fi
  fetch "http://$HOST:$PORT${page}" "$default_out" || fail=1

  for lang in "${LANGS[@]}"; do
    if [ "$page" = "/" ]; then
      lang_out="$DIST/_i18n/$lang/index.html"
    else
      lang_out="$DIST/_i18n/$lang${page}index.html"
    fi
    fetch "http://$HOST:$PORT${page}?lang=$lang" "$lang_out" || fail=1
  done
done

echo "── build-static: rendu 404 ─────────────────────────────────"
# 404.php renvoie volontairement le code HTTP 404 — c'est le comportement attendu.
fetch "http://$HOST:$PORT/404.php" "$DIST/404.html" 404 || fail=1

echo "── build-static: rendu admin-blog ──────────────────────────"
fetch "http://$HOST:$PORT/admin-blog/" "$DIST/admin-blog/index.html" || fail=1
fetch "http://$HOST:$PORT/admin-blog/login.php" "$DIST/admin-blog/login.html" || fail=1

kill "$PHP_PID" 2>/dev/null || true
trap - EXIT

if [ "$fail" != "0" ]; then
  echo "❌ build-static: au moins une page a échoué (voir warnings ci-dessus)"
  exit 1
fi

echo "── build-static: copie des assets statiques ────────────────"
cp -R "$ROOT/assets" "$DIST/assets"
cp "$ROOT/favicon.ico" "$DIST/favicon.ico"
cp "$ROOT/robots.txt" "$DIST/robots.txt"
cp "$ROOT/sitemap.xml" "$DIST/sitemap.xml"

n_html=$(find "$DIST" -name "*.html" | wc -l | tr -d ' ')
echo "── build-static: terminé — $n_html fichiers HTML générés dans dist/ ──"
