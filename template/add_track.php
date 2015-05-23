Dodawanie Utworu<br><br>

<fieldset>
    <legend> 1. Dodaj pliki </legend>
    <form id="upload" method="post" action="?page=add_track_action" enctype="multipart/form-data">
        <div id="drop">
            Przeciagnij plik .jpg i .mp3<br/> <br/>

            <a>Wybierz [jpg, mp3]</a>
            <input type="file" name="up2" multiple />
        </div>
        <ul>
        </ul>
    </form>

    <form action="?page=add_track_final_action" method="POST">
        <dl>
            <dt><label for="track_name">Tytul Utworu:</label></dt>
            <dd><input id="track_name" type="text" name="track_name" placeholder="Wpisz Tytul Utworu"></dd>
            <dt><input type="submit" value="Dodaj"></dt>
        </dl>
    </form>
</fieldset>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="assets/js/jquery.knob.js"></script>
<script src="assets/js/jquery.ui.widget.js"></script>
<script src="assets/js/jquery.iframe-transport.js"></script>
<script src="assets/js/jquery.fileupload.js"></script>
<script src="assets/js/script.js"></script>