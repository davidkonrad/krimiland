# :bulb:  Download / se PDF'en
Det væsentlige er jo PDF'en, og hvis man ikke i forvejen kender GitHub kan det måske virke forvirrende. Du kan klikke på `estoniaferrydisaster.net.pdf` ovenover, men filen er halvstor (~30mb, 445 sider) og tager tid at renderere indlejret på siden.  Desværre ignorerer GitHub  `target` i links. Alternativer:

1. Åbn https://github.com/davidkonrad/krimiland/raw/main/estoniaferrydisaster.net.pdf i et nyt faneblad, eller
2. Download zip https://github.com/davidkonrad/krimiland/archive/refs/heads/main.zip, eller
3. Kopier hele repositoriet `$ git clone https://github.com/davidkonrad/krimiland.git`

# Krimiland, opfølgning
Dette er relateret til Krimiland afsnit 10. Hør programmerne her https://www.radio4.dk/program/krimiland/

Der bliver i afsnittet omtalt et interessant website, [estoniaferrydisaster.net](https:/www.estoniaferrydisaster.net)
 -- et site som desværre er skæmmet af, at være svært tilgængelig. Orris ærgrer sig over, at man ikke kan få fat i rapporten i papirformat eller PDF, og der jokes med, om ikke nogle af lytterne kan tage opgaven på sig. Undertegnede tog handsken op, og dette er hvad man sådan lige kunne automatisere via konsollen. 

Resultatet er langt fra komplet!! Og hvorfor og hvordan det er gjort beskrives nedenfor. Den genererede PDF er pt. 374 sider, men den er næppe færdig, og der er uorden i indholdet. Der mangler (tilsyneladende) en masse enkeltstående sider. 

Så hvis nogle er interesserede i at forbedre PDF'en, opret et issue med et link (eller det der ligner), til den eller de sider der mangler, så kan jeg hente dem ned og køre de 4 step forfra og dermed fabrikere en bedre PDF. Det gælder også hvis du mener der skal ændres i ordenen af indholdet, eller whatever. 

De manglende sider kan heller ikke indlæses / indekseres af t.ex Googles søgerobot. Men inden man spekulerer alt for meget over dette, så er det min bedømmelse, at sitet var state of the art dengang det blev bygget / lanceret. Så det er ikke en "villet" skyggetilværelse, teknikken har blot udviklet sig enormt siden.
 
Er du dedikeret kan du blive collaborator / medejer af projektet, så du kan lave opdateringer selv. Jeg er også parat til at overdrage hele molevitten til Krimiland. 

Krimiland kan "clone" projektet og køre det videre, så sletter/skjuler jeg dette repositorie.


# estoniaferrydisaster dot net rapport som PDF

### Fremgangsmåde

Som det blev antydet i programmet, så er hjemmesiden med den tyske rapport ikke blot "gammeldags", men også særdeles vanskelig at navigere i. Der benyttes noget der kaldes `<frameset>` og `<iframe>`'s, samt total oldschool navigeringsmarkup placeret i `<map>` og `<area>`'s. Dette gør i praksis sitet umulig at crawle programmeringsmæssigt.


#### Download
Følger man de enkelte links manuelt kan man dog finde den relative sti til rapportens index - `estonia final report/Contents.htm` - og via den downloade "hele" rapporten. Ja, altså kun de sider der direkte linkes til, og så deres undersider rekursivt. Men visse dele af rapporten er kun tilgængelig via disse `<area>` tags. I Linux kan man skrive:

```bash
$ wget -r -l 1 https://www.estoniaferrydisaster.net/estonia%20final%20report/Contents.htm
```

Det resulterer i et `/www.estoniaferrydisaster.net/..` katalog (se ovenover), hvor hele rapporten ligger i 123 (spøjst) forskellige filer, med navne som `chapter42.htm` og `germanxperts.htm`. Altså i uskøn orden, men trods alt med sigende logiske filnavne, man kan relatere til ift. indekset.

#### Samling
Inde fra kataloget genereres en liste over samtlige filer:

```bash
$ ls -R > filer.txt
```

Fordi filerne har sigende navne er det nemt (men omstændeligt, og har opgivet for nærværende at blive færdig) at sortere i listen så rækkefølgen stemmer overens med indekset og en kapitel 1-43 orden. Når det er gjort kan man med lidt mere konsolmagi samle hele rapporten: 

```bash
$ xargs < filer.txt cat > ../estoniaferrydisaster.net.html
```
#### Billeder
Det er kun selve teksten der er hentet ned og samlet, og desværre er samtlige billedreferencer baseret på relative `src`-stier, så rapporten er fuld af "tomme ruder".  For at fikse dette kan man hive samtlige billeder ned lokalt, og lægge dem i `/estonia final report/`-kataloget. Det giver mening hvis man vil sikre sin egen komplette kopi, men for at generere en PDF er det tilstrækkeligt at ændre de relative stier til absolutte:

```php
<?php
$from = 'www.estoniaferrydisaster.net/estoniaferrydisaster.net.html';
$to = 'www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html';
$dom = new DOMDocument('1.0');
$dom->loadHTMLFile($from);
$images = $dom->getElementsByTagName('img');
foreach($images as $image) {
   $src = str_replace('../', 'estonia%20final%20report/', $image->getAttribute('src')); 
   $image->setAttribute('src', 'https://www.estoniaferrydisaster.net/'.$src);
}
$dom->save($to);
?>
```

#### PDF
Brug chromium-browserens indbyggede print -> destination -> save as PDF. 

## Historik
Liste over manuelle tilføjelser. Dvs. afsnit, bilag, billeder, de såkaldte "enclosures" o.lign som ikke blev fanget af den oprindelige rekursive `wget`, og som rummer t.ex indscannede emails og underkataloger med billeder.  Alle tilføjelser er en firetrinsraket: Download det relevante materiale, ret `filer.txt`, kør `fix-billeder.php`, generer PDF via en Chromium-browser.

***06.04.2021***

Tilføjede bilag 2.4.2.21, dvs
```bash
$ wget -r -l 1 https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.21.htm
```
samt 
```bash
https://www.estoniaferrydisaster.net/estonia%20final%20report/2.5.3.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.5.60.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.59.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.47.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.57.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.4.55.htm
https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.27.htm
```

***07.04.2021***

Tilføjede bilag 12.2.139, dvs
```bash
wget -r -l 1 https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/12.2.139.htm
```
samt
```bash
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


wget -r -l 1 https://www.estoniaferrydisaster.net/estonia%20final%20report/enclosures%20HTM/2.4.2.20.htm



```