<?php
?>

<form id="bible" method="post" action="bible_search_m.php" class="form">
    <div class="bible">
                 <div class="toolbar">
                    <h1>The Bible</h1>
                    <a class="button flipleft" href="#first">Main</a>
                 </div>

    <ul class="rounded">
        <li>
            <select id="book" name="book">
                <optgroup label="Old Testament">
                    <option value="genesis.txt">Genesis</option>
                    <option value="exodus.txt">Exodus</option>
                    <option value="leviticus.txt">Leviticus</option> 
                    <option value="numbers.txt">Numbers</option> 
                    <option value="duteronomy.txt">Deuteronomy</option> 
                    <option value="joshua.txt">Joshua</option> 
                    <option value="judges.txt">The Book of Judges</option> 
                    <option value="ruth.txt">The Book of Ruth</option> 
                    <option value="1kings.txt">The First Book of Samuel</option>
                    <option value="2kings.txt">The Second Book of Samuel</option>
                    <option value="3kings.txt">The First Book of the Kings</option>
                    <option value="4kings.txt">The Second Book of the Kings</option>
                    <option value="1chronicles.txt">The First Book of the Chronicles</option>
                    <option value="2chronicles.txt">The Second Book of the Chronicles</option>
                    <option value="ezra.txt">Ezra</option> 
                    <option value="nehemiah.txt">The Book of Nehemiah</option>
                    <option value="esther.txt">The Book of Esther</option>
                    <option value="job.txt">The Book of Job</option>
                    <option value="psalms.txt">The Book of Psalms</option>
                    <option value="proverbs.txt">The Proverbs</option>
                    <option value="ecclesiastes.txt">Ecclesiastes or The Preacher</option>
                    <option value="song_of_solomon.txt">The Song of Solomon</option>
                    <option value="isaiah.txt">The Book of the Prophet Isaiah</option>
                    <option value="jeremiah.txt">The Book of the Prophet Jeremiah</option>
                    <option value="lamentations.txt">The Lamentations of Jeremiah</option>
                    <option value="ezekiel.txt">The Book of the Prophet Ezekiel</option>
                    <option value="daniel.txt">The Book of Daniel</option>
                    <option value="hosea.txt">Hosea</option>
                    <option value="joel.txt">Joel</option>
                    <option value="amos.txt">Amos</option>
                    <option value="obadiah.txt">Obadiah</option>
                    <option value="jonah.txt">Jonah</option>
                    <option value="micah.txt">Micah</option>
                    <option value="nahum.txt">Nahum</option>
                    <option value="habakkuk.txt">Habakkuk</option>
                    <option value="zephaniah.txt">Zephaniah</option>
                    <option value="haggai.txt">Haggai</option>
                    <option value="zechariah.txt">Zechariah</option>
                    <option value="malachi.txt">Malachi</option>
                </optgroup>
                <optgroup label="New Testament">
                    <option value="mark.txt">Saint Mark</option>
                    <option value="luke.txt">Saint Luke</option>
                    <option value="john.txt">Saint John</option>
                    <option value="acts.txt">The Acts</option>
                    <option value="romans.txt">Romans</option>
                    <option value="1corinthians.txt">First Corinthians</option>
                    <option value="2corinthians.txt">Second Corinthians</option>
                    <option value="galatians.txt">Galatians</option>
                    <option value="ephesians.txt">Ephesians</option>
                    <option value="1philippians.txt">Philippian</option>
                    <option value="colossians.txt">Colossians</option>
                    <option value="1thessalonians.txt">First Thessalonians</option>
                    <option value="2thessalonians.txt">Second Thessalonians</option>
                    <option value="1timothy.txt">First Timothy</option>
                    <option value="2timothy.txt">Second Timothy</option>
                    <option value="titus.txt">Titus</option>
                    <option value="1philemon.txt">Philemon</option>
                    <option value="hebrews.txt">Hebrews</option>
                    <option value="james.txt">James</option>
                    <option value="jude.txt">The General Epistle of Jude</option>
                    <option value="1peter.txt">The First Epistle General of Peter</option>
                    <option value="2peter.txt">The Second General Epistle of Peter</option>
                    <option value="1john.txt">The First Epistle General of John</option>
                    <option value="2john.txt">The Second Epistle General of John</option>
                    <option value="3john.txt">The Third Epistle General of John</option>
                    <option value="revelation.txt">Revelations</option>
                </optgroup>
            </select>
        <li><input type="number" value="" name="chapter" placeholder="Chapter" id="chapter" /></li>                    
        <li><input type="number" value="" name="startverse" placeholder="Start Verse" id="startverse" /></li>                    
        <li><input type="number" value="" name="endverse" placeholder="End Verse" id="endverse" /></li>
   </ul>
<a style="margin:0 10px;color:rgba(0,0,0,.9)" href="#" class="submit whiteButton">Submit</a>
    </div>
</form>

