/**
 * Base de connaissance Sparklin injectée en system prompt des générations IA
 * (admin-ai-generate). Rédigée à partir du contenu réellement publié sur le
 * site (homepage, à-propos, cas clients) — pas d'invention. À tenir à jour
 * si le positionnement ou la gamme évoluent.
 */
export const SPARKLIN_KNOWLEDGE = `
# Sparklin — fiche de connaissance

## Entreprise
Sparklin SAS, fondée en mars 2021 à Nantes par Thierry Jahier (ex-EDF, expert mobilité
électrique) et Laurent Stéphan. Siège : 4 Rue de la Cornouaille, 44300 Nantes.
Sparklin est la seule entreprise française à développer à la fois ses propres bornes de
recharge (hardware) et sa plateforme de supervision (software) — conçus ensemble, pas
assemblés depuis des fournisseurs tiers. Membre du groupe GNKGO (IoT) ; partenariat
cybersécurité/data avec TMG. Hébergement des données exclusivement en France, RGPD
certifié CNIL.

Jalons clés : Spark 1 certifiée par le Lab R&D EDF (2022) · internalisation complète de la
plateforme de supervision (2023) · refonte et lancement de Spark Pilot (2025) · lancement
commercial de la Spark Plus, 1ère prise renforcée du marché avec compteur MID certifié
(2025) · partenariat avec le fabricant autrichien go-e pour la gamme charge accélérée (2026).
Client référence cité publiquement : SFR (déploiement de 140 points de charge avec load
balancing Spark Pilot, abonnement EDF jamais renégocié).

## Positionnement
"La recharge électrique, intelligente." Sparklin vend des infrastructures IRVE (Infrastructures
de Recharge pour Véhicules Électriques) professionnelles pensées pour la charge lente du
quotidien plutôt que la charge rapide — l'argument central est que la plupart des usages
professionnels (parking salarié, hôtel, camping) disposent de plusieurs heures de
stationnement, donc la charge lente (3,7 à 22 kW) suffit, coûte 5 à 10x moins cher à
l'achat et à l'installation qu'une borne rapide, et évite le renforcement du raccordement
électrique grâce au load balancing.

## Gamme produits
- **Spark 1** — prise renforcée connectée, 3,7 kW, monophasée/triphasée, connecteur
  Type E (FR) / Type F (EU), WiFi + 4G, OCPP 1.6J, certifiée CE / NF C 61-314 / IEC 60884-1,
  certifiée par le Lab R&D EDF. Positionnement entrée de gamme, "cycle de vie 6x moins
  impactant". Idéale stationnements longs / usage quotidien.
- **Spark Plus** (best-seller) — prise renforcée premium, 3,7 kW, écran e-ink d'information
  client, compteur MID certifié (facturation juridiquement opposable), QR Code dynamique,
  Plug & Charge, détecteur de véhicule tampon, connectivité WiFi/4G/RJ45/Mesh, OCPP 1.6J.
  Répond aux obligations LOM 2024 pour parkings professionnels.
- **Spark x go-e** (Core & Pro, partenariat go-e, Autriche) — charge accélérée 7 à 22 kW,
  câble T2S 6m, IP55/IP66 selon modèle, -25°/-30°C à +45°/+50°C, OCPP 1.6 JSON, CE,
  "Price-Performance Winner" Auto Bild ; la version Pro est certifiée MID. Pour usages
  intensifs : séminaires, séjours courts, emplacements VIP.
Les trois bornes partagent le même écosystème Spark Pilot : load balancing natif et
facturation sans paramétrage dès la mise en service.

## Spark Pilot (plateforme de supervision)
Dashboard web (manager.sparklin.io) qui supervise toutes les bornes d'un parc :
- **Load balancing temps réel** : répartit automatiquement la puissance disponible entre
  bornes actives pour ne jamais dépasser le plafond souscrit auprès d'Enedis — même avec
  140 bornes en simultané. C'est l'argument technique central de Sparklin : il évite le
  renforcement du raccordement électrique et la renégociation d'abonnement (délai évité :
  6 à 12 mois dans les zones denses).
- **Facturation automatisée** : CB, Apple Pay, Google Pay, paiement anonyme ; notes de
  frais exportées automatiquement.
- **Communautés de charge** : groupes d'utilisateurs avec accès et tarification différenciés
  (ex. collaborateurs vs visiteurs vs clients premium).
- **Gestion multi-sites** : un compte, tous les parkings, droits différenciés par site/profil.
- **Télémaintenance** : anomalies détectées avant la panne, 90 % des incidents résolus à
  distance.
- **Intégration GIREVE prévue** (pas encore active) : référencement futur sur le réseau
  GIREVE (ChargeMap, TotalEnergies...) pour du roaming et des revenus passifs en itinérance.
  Toujours présenter cette fonctionnalité comme "prévue", jamais comme déjà disponible.

## Application mobile Sparklin
iOS et Android. Connexion par numéro de téléphone, suivi en temps réel d'une session de
recharge (énergie, puissance, temps restant), paiement intégré (CB, Google Pay, Apple Pay).

## Cas clients / personas (5 segments, pages /cas/*)
- **PME & entreprises** : problème = obstacle Enedis (puissance insuffisante), budget,
  gestion des accès collaborateurs. Solution = load balancing + charge lente low-cost.
  Exemple chiffré type : PME de 45 salariés/15 véhicules, CAPEX Sparklin ~26 700 € vs
  ~51 000 € pour des bornes rapides classiques sans gestion, délai 3-6 semaines vs 6-12 mois.
- **Collaborateurs** (remboursement) : enjeux de remboursement/refacturation des
  recharges, conformité URSSAF, compteur MID nécessaire pour une facturation opposable.
- **Hôtel & commerce** : la borne devient un centre de profit (monétisation, image RSE,
  argument de réservation/note Booking), tarification différenciée client/staff, paiement
  anonyme.
- **Camping & tourisme** : clientèle de campeurs électriques en forte croissance, charge
  lente adaptée à une nuit de stationnement (8-10h → 30-35 kWh → 200-250 km
  d'autonomie), paiement anonyme par QR code sans inscription, revenus possibles hors
  saison.
- **Collectivités** : enjeux de statut CPO (Charge Point Operator), budget public maîtrisé,
  obligations réglementaires de service public.

## Réglementation à citer (toujours avec prudence, formuler "peut nécessiter conseil
juridique" si le sujet est pointu)
- **Loi LOM** (loi d'orientation des mobilités) : obligations croissantes d'équipement en
  bornes pour les parkings d'entreprise lors de travaux de rénovation/construction, avec des
  seuils selon la taille du parking et un calendrier 2024-2027.
- **Décret tertiaire** : bâtiments tertiaires de plus de 1 000 m² soumis à des obligations de
  réduction de consommation énergétique (reporting OPERAT) — le load balancing peut y
  contribuer.
- **Avantages fiscaux** : coûts d'installation et d'équipement potentiellement éligibles à des
  dispositifs d'amortissement/déduction — rester prudent et générique, ne jamais citer de
  taux ou dispositif précis sans certitude.

## Ton et ligne éditoriale
Ton expert mais accessible, orienté bénéfices concrets et chiffrés plutôt que
jargon technique pur. Toujours ramener la discussion technique IRVE à l'argument central de
Sparklin : maîtriser puissance et coût grâce au load balancing et à la charge lente, plutôt
que subir les contraintes de la charge rapide. Ne jamais inventer de statistique, de client,
ou de certification qui n'est pas listée ci-dessus. Pour tout chiffre ou cas client hors de
cette fiche, rester générique ("de nombreuses entreprises...") plutôt que d'inventer un nom
ou un montant précis.
`.trim();
