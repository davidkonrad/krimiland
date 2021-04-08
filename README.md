# :ferry:  Download / se PDF'er 
Du kan klikke på `estoniaferrydisaster.net.pdf` ovenover, men filen er halvstor og tager tid at renderere indlejret på siden.  Desværre ignorerer GitHub  `target` i links. 

#### Selve rapporten
<img src="https://www.flaticon.com/svg/vstatic/svg/136/136522.svg?token=exp=1617902671~hmac=586c0d72039ab9ac322c1a1699f31a09" width="25">  https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.pdf <sup>(399s, ∼23mb)</sup>

#### Bilag
GitHub er ikke så glad for store filer (>50mb) så bilagene må splittes yderligere op i bilag 1-50, 51-100, 101-150 osv.  Alle bilagsreferencer er forsynet med et fortløbende bilagsnummer i enden. T.ex bilag 2.4.2.22, her er det unikke bilagsnummer 22, og det hører til kapitel 2, afsnit 4, underafsnit 2.

<img src="https://www.flaticon.com/svg/vstatic/svg/136/136522.svg?token=exp=1617902671~hmac=586c0d72039ab9ac322c1a1699f31a09" width="25">  https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.bilag.pdf <sup>(163s, ∼22mb)</sup><br>
<img src="https://www.flaticon.com/svg/vstatic/svg/136/136522.svg?token=exp=1617902671~hmac=586c0d72039ab9ac322c1a1699f31a09" width="25">  https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.bilag_51.pdf <sup>(171s, ∼18mb)</sup><br>
<img src="https://www.flaticon.com/svg/vstatic/svg/136/136522.svg?token=exp=1617902671~hmac=586c0d72039ab9ac322c1a1699f31a09" width="25">  https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.bilag_101.pdf <sup>(487s, ∼38mb)</sup><br>
<img src="https://www.flaticon.com/svg/vstatic/svg/136/136522.svg?token=exp=1617902671~hmac=586c0d72039ab9ac322c1a1699f31a09" width="25">  https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.bilag_151.pdf <sup>(205s, ∼15mb)</sup><br>
<img src="https://www.flaticon.com/svg/vstatic/svg/136/136522.svg?token=exp=1617902671~hmac=586c0d72039ab9ac322c1a1699f31a09" width="25">  https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.bilag_201.pdf <sup>(269s, ∼26mb)</sup><br>
<img src="https://www.flaticon.com/svg/vstatic/svg/136/136522.svg?token=exp=1617902671~hmac=586c0d72039ab9ac322c1a1699f31a09" width="25">  https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.bilag_401.pdf <sup>(568s, ∼38mb)</sup>

Der er nogle gevaldige huller i bilagsmaterialet omkring kapitel 17-21. Jeg frasorterer "døde" bilag, linksne' virker men billedstierne fungerer ikke. Der er lignende "huller" omkring billedmateriale i selve rapporten. Det virker som et katalog er blevet slettet, eller at den server billederne lå på er slukket.  Brandærgerligt, men i forhold til den totale mængde bilag dog alligevel beskedent. 

Status: Rapporten er samlet, er vel nået igennem måske 70% bilag. Se [bilag.md](bilag.md). Herefter vil jeg prøve at fylde hullerne op, har allerede fundet "skjulte" bilag. Skjulte fordi der ikke linkes til dem nogle steder, men eftersom rapportens indhold er bygget logisk op, har jeg haft held med at gætte stien til adskillige manglende bilag. I listen er de markeret med udråbstegn. 

#### Alle filer
1. Download zip https://github.com/davidkonrad/krimiland/archive/refs/heads/main.zip <sup>(stor!)</sup>, eller
2. Kopier hele repositoriet `$ git clone https://github.com/davidkonrad/krimiland.git`

# Krimiland, opfølgning
Dette er relateret til Krimiland afsnit 10. Hør programmerne her https://www.radio4.dk/program/krimiland/

Der bliver i afsnittet omtalt et interessant website, [estoniaferrydisaster.net](https:/www.estoniaferrydisaster.net)
 -- et site som desværre er skæmmet af, at være svært tilgængelig. Orris ærgrer sig over, at man ikke kan få fat i rapporten i papirformat eller PDF, og der jokes med, om ikke nogle af lytterne kan tage opgaven på sig.  Dette er et foreløbigt resultat. 

**Forslag / rettelser / forglemmelser, alle er mere end velkomne til at oprette et [issue](https://github.com/davidkonrad/krimiland/issues)**. 

# estoniaferrydisaster.net rapport som PDF

### Fremgangsmåde

Som det blev antydet i programmet, så er hjemmesiden med den tyske rapport ikke blot "gammeldags", men også særdeles vanskelig at navigere i. Der benyttes noget der kaldes `<frameset>` og `<iframe>`'s, samt total oldschool navigeringsmarkup placeret i `<map>` og `<area>`'s. Dette gør i praksis sitet umulig at crawle programmeringsmæssigt.


#### Download
Man kan hente rapportens indeks og de filer denne linker til rekursivt med 

```bash
$ wget -r -l 1 https://www.estoniaferrydisaster.net/estonia%20final%20report/Contents.htm
```

Det resulterer i et `/www.estoniaferrydisaster.net/..` katalog (se ovenover), hvor hele rapporten ligger i forskellige filer med navne som `chapter42.htm` og `germanxperts.htm`. Altså i uskøn orden, men trods alt med sigende logiske filnavne, man kan relatere til ift. indekset.

#### Samling
Inde fra kataloget genereres en liste over samtlige filer:

```bash
$ ls -R > _rapport.txt
```

Fordi filerne har sigende navne kan det lade sig gøre at samle teksterne til et layout der honorerer rapportens "flow". Jeg er ved at gå rapporten igennem for at høste bilags-links, og kan sjusse mig frem til den korrekte orden undervejs.  Man kan samle rapporten med

```bash
$ xargs < _rapport.txt cat > ../estoniaferrydisaster.net.html
```
Har valgt at placere bilagene i deres egen PDF.  Det bliver for rodet at lægge links i forlængese af de afsnit de logisk hører til (som det blev gjort i starten) og at placere dem alle f.eks i bunden vil bare give masser af scrolleri i et kæmpestort dokument. De fleste bilag er forsynet med klar nummerering, og lagt i fortløbende  nummerorden. Referencer til downloadede bilag ligger i `_bilag.txt`, og for at generere bilag-filen kan man skrive 

```bash
$ xargs < _bilag.txt cat > ../estoniaferrydisaster.net.bilag.html
$ xargs < _bilag_51.txt cat > ../estoniaferrydisaster.net.bilag_51.html
$ xargs < _bilag_101.txt cat > ../estoniaferrydisaster.net.bilag_101.html
$ xargs < _bilag_151.txt cat > ../estoniaferrydisaster.net.bilag_151.html
$ xargs < _bilag_201.txt cat > ../estoniaferrydisaster.net.bilag_201.html
$ xargs < _bilag_401.txt cat > ../estoniaferrydisaster.net.bilag_401.html
```

#### Billeder
Det er kun selve teksten der er hentet ned og samlet, og desværre er samtlige billedreferencer baseret på relative `src`-stier, så rapporten er fuld af "tomme ruder".  For at fikse dette kan man hive samtlige billeder ned lokalt, og lægge dem i `/estonia final report/`-kataloget. Det giver mening hvis man vil sikre sin egen komplette kopi, men for at generere en PDF er det tilstrækkeligt at ændre de relative stier til absolutte:

Et lille script, [fix.php](fix.php) retter et par fejl:

* Ændrer relative billed-stier til absolutte
* Trækker `<title>` ud af bilag, og placerer dem som `<h1>` headere foran indholdet
* Fjerner "de blå pile" som bare fylder op

#### PDF
Åbn `estoniaferrydisaster.net.fixed.html` / `estoniaferrydisaster.net.bilag.fixed.html` (osv) med en chromium-browser, og brug den indbyggede print -> destination -> save as PDF.  Som genvej kan man generere PDF'erne i konsollen:

```bash
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.fixed.html
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag_51.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.fixed.html
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag_101.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.fixed.html
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag_151.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_151.fixed.html
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag_201.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_201.fixed.html
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag_401.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_401.fixed.html
```
Men `--print-to-pdf` medtager *altid* title, en grim header og sidetal. Så "pæne" PDF'er må fabrikeres manuelt.

## Historik
se [bilag.md](bilag.md).
