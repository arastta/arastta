/** reworked 2015.08 http://osworx.net */
(function ($) {
  $.extend($.summernote.lang, {
    'de-DE': {
      font: {
        bold: 'Fett',
        italic: 'Kursiv',
		underline: 'Unterstrichen',
        strikethrough: 'Durchgestrichen',
		subscript: 'Tiefgestellt',
		superscript: 'Hochgestellt',
		clear: 'Schriftart entfernen',
        height: 'Zeilenhöhe',
		name: 'Schriftart',
        size: 'Schriftgröße'
      },
      image: {
        image: 'Grafik',
        insert: 'Grafik einfügen',
        resizeFull: 'Originalgröße',
        resizeHalf: 'Größe 1/2',
        resizeQuarter: 'Größe 1/4',
        floatLeft: 'Linksbündig',
        floatRight: 'Rechtsbündig',
        floatNone: 'Kein Textfluss',
        shapeRounded: 'Rahmen: Abgerundet',
        shapeCircle: 'Rahmen: Kreisförmig',
        shapeThumbnail: 'Rahmen: Thumbnail',
        shapeNone: 'Kein Rahmen',
        dragImageHere: 'Bild mit Maus hierher ziehen',
        selectFromFiles: 'Datei auswählen',
        maximumFileSize: 'Maximale Dateigröße',
        maximumFileSizeError: 'Maximale Dateigröße überschritten',
        url: 'Grafik URL',
        remove: 'Grafik entfernen'
      },
      link: {
        link: 'Link',
        insert: 'Link einfügen',
        unlink: 'Link entfernen',
        edit: 'Editieren',
        textToDisplay: 'Anzeigetext',
        url: 'Ziel des Links',
        openInNewWindow: 'In neuem Fenster öffnen'
      },
      table: {
        table: 'Tabelle'
      },
      hr: {
        insert: 'Horizontale Linie einfügen'
      },
      style: {
        style: 'Stil',
        normal: 'Normal',
        blockquote: 'Zitat',
        pre: 'Quellcode',
        h1: 'Überschrift 1',
        h2: 'Überschrift 2',
        h3: 'Überschrift 3',
        h4: 'Überschrift 4',
        h5: 'Überschrift 5',
        h6: 'Überschrift 6'
      },
      lists: {
        unordered: 'Aufzählung',
        ordered: 'Nummerierung'
      },
      options: {
        help: 'Hilfe',
        fullscreen: 'Vollbild',
        codeview: 'HTML-Code anzeigen'
      },
      paragraph: {
        paragraph: 'Absatz',
        outdent: 'Einzug vergrößern',
        indent: 'Einzug verkleinern',
        left: 'Links ausrichten',
        center: 'Zentriert ausrichten',
        right: 'Rechts ausrichten',
        justify: 'Blocksatz'
      },
      color: {
        recent: 'Letzte Farbe',
        more: 'Mehr Farben',
        background: 'Hintergrundfarbe',
        foreground: 'Schriftfarbe',
        transparent: 'Transparenz',
        setTransparent: 'Transparenz setzen',
        reset: 'Zurücksetzen',
        resetToDefault: 'Auf Standard zurücksetzen'
      },
      shortcut: {
        shortcuts: 'Tastenkürzel',
        close: 'Schließen',
        textFormatting: 'Textformatierung',
        action: 'Aktion',
        paragraphFormatting: 'Absatzformatierung',
        documentStyle: 'Dokumentenstil'
      },
      history: {
        undo: 'Rückgängig',
        redo: 'Wiederholen'
      }
	// + OSWorX
	  ,
      video: {
      	video: 'Video',
	  	videoLink: 'Videolink',
	  	insert: 'Video  einfügen',
	  	url: 'Video URL',
	  	providers: '(YouTube, Vimeo, Vine, Instagram, DailyMotion oder Youku)'
      }
    }
  });
})(jQuery);
