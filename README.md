# :ferry:  Download / se PDF'er 
Du kan klikke på `estoniaferrydisaster.net.pdf` ovenover, men filen er halvstor og tager tid at renderere indlejret på siden.  Desværre ignorerer GitHub  `target` i links. 

#### Selve rapporten
Åbn https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.pdf i et nyt faneblad (pt ∼24mb, 376 sider, mangler stadig en del sortering)

#### Bilag
GitHub er ikke så glad for store filer (>50mb) så bilagene må splittes yderligere op i bilag 1-50, 51-100, 101-150 osv.  Alle bilagsreferencer er forsynet med et fortløbende bilagsnummer i enden. T.ex bilag 2.4.2.22, her er det unikke bilagsnummer 22, og det hører til kapitel 2, afsnit 4, underafsnit 2.

https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.bilag.pdf <sup>(∼144s, ∼21mb)</sup>
https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.bilag_51.pdf <sup>(∼167s, ∼18mb)</sup>
https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.bilag_101.pdf <sup>(∼370s, ∼30mb)</sup>

Vi har altså rundet 1,000 sider, men det kan man ikke regne med. T.ex fylder hvert billede en A4-side, uanset størrelse, og de er forsynet med statiske, hardcodede  `width` / `height`-værdier. Vil forsøge at trække bilagenes `title` ud som billedoverskrifter, og sætte størrelserne på dem fri.

#### Alle filer
1. Download zip https://github.com/davidkonrad/krimiland/archive/refs/heads/main.zip, eller
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
```

#### Billeder
Det er kun selve teksten der er hentet ned og samlet, og desværre er samtlige billedreferencer baseret på relative `src`-stier, så rapporten er fuld af "tomme ruder".  For at fikse dette kan man hive samtlige billeder ned lokalt, og lægge dem i `/estonia final report/`-kataloget. Det giver mening hvis man vil sikre sin egen komplette kopi, men for at generere en PDF er det tilstrækkeligt at ændre de relative stier til absolutte:

```php
<?php
function fix($from, $to) {
  $dom = new DOMDocument('1.0');
  $dom->loadHTMLFile($from);
  $images = $dom->getElementsByTagName('img');
  foreach($images as $image) {
    $src = str_replace('../', 'estonia%20final%20report/', $image->getAttribute('src'));
    if (substr($src, 0, 7) === 'images/') {
      $src = 'estonia%20final%20report/'.$src;
    }
    $image->setAttribute('src', 'https://www.estoniaferrydisaster.net/'.$src);
  }
  $dom->save($to);
}
fix('www.estoniaferrydisaster.net/estoniaferrydisaster.net.html',  'www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html');
?>
```
Se flere detaljer i `fix-billeder.php'.

#### PDF
Åbn `estoniaferrydisaster.net.fixed.html` / `estoniaferrydisaster.net.bilag.fixed.html` (osv) med en chromium-browser, og brug den indbyggede print -> destination -> save as PDF.  Som genvej kan man generere PDF'erne i konsollen:

```bash
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.fixed.html
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag_51.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.fixed.html
$ chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag_101.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.fixed.html
```
Men `--print-to-pdf` medtager *altid* title, en grim header og sidetal. Så "pæne" PDF'er må fabrikeres manuelt.

## Historik
Liste over manuelle tilføjelser. Dvs. afsnit, bilag, billeder, de såkaldte "enclosures" o.lign som ikke blev fanget af den oprindelige rekursive `wget`, og som rummer t.ex indscannede emails og underkataloger med billeder. 

```bash
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.21.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/2.5.3.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.5.60.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.59.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.47.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.57.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.55.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.27.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/12.2.139.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.3.1.5.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.3.1.6.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.3.1.7.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.3.1.8.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2..3.1.9.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.25.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.3.116.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.10.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.13.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.11.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.12.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.13.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.14.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.15.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.16.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.17.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.18.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.1.19.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.20.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.22.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.23.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.24.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.26.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.28.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.29.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.30.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.31.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.32.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.33.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.34.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.35.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.36.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.37.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/9.1.134.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.38.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.39.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.40.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.41.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.42.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.43.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.44.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.44.1.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.44.2.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/enc%202/21.2.4.278.htm //broken
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/enc%202/12.5.183.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.3.45.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.46.htm //!
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.50.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.51.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.52.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.53.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.54.htm //!
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.56.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.5.61.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.5.62.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.6.63.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.6.64.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.6.65.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.6.66.htm 
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.2.67.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.2.68.htm //!
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.2.69.htm //!
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.2.70.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.2.71.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.2.73.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.2.74.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.2.75.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.2.76.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.3.77.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.3.78.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.4.78.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.4.79.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.4.80.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.6.4.81.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.2.3.82.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.3.83.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.86.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.87.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.88.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.89.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.90.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.91.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.92.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.93.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.94.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.95.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.96.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.97.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.98.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.99.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.100.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.101.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.102.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.103.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.104.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/3.4.105.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/4.106.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/5.107.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/5.2.108.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/5.2.109.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/5.2.110.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/5.3.111.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.1.112.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.1.113.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.2.114.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.3.115.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.3.116.1.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.4.117.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.4.118.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.4.119.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.4.120.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.4.121.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.4.122.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.5.2.125.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.5.2.126.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.5.2.127.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/6.5.1.123.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/7.3.2.128.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/7.3.4.129.1.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/7.3.4.129.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/9.1.130.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/9.1.131.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/9.1.132.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/9.1.133.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/9.1.134.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/9.1.135.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/9.1.136.htm //!
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/9.3.137.htm








```