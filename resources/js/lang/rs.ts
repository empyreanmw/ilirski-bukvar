export default {
    sidebar: {
        history: "Istorija",
        theology: "Teologija",
        health: "Zdravlje",
        settings: "Podešavanja",
        podcasts: "Podkast",
        favorites: "Omiljeni",
        download_manager: "Preuzimanje Sadrzaja",
        movies: "Filmovi",
        suggestions: "Sugestije",
    },
    general: {
        history: "Istorija",
        movie: "Filmovi",
        theology: "Teologija",
        podcasts: "Podkast",
        health: "Zdravlje",
        video: "Video",
        book: "Knjige",
        cartoon: "Crtani",
        series: "Serijali",
        favorites: "Omiljeni",
        download_manager: "Preuzimanje Sadrzaja",
        loading: "Učitvanje",
        download_status: {
            done: 'Završeno',
            in_progress: "U progresu",
            queued: "Na čekanju",
            cancelled: "Prekinuto",
            failed: "Neuspešno"
        },
        no_video: "Nema dostupnih videa",
        no_video_description: "Trenutno ne postoji ni jedan dostupan video",
        no_book: "Nema dostupnih knjiga",
        no_book_description: "Trenutno ne postoji ni jedna dostupna knjiga",
        no_series: "Nema dostupnih serijala",
        no_series_description: "Trenutno ne postoji ni jedan dostupan serijal",
        no_movie: "Nema dostupnih filmova",
        no_movie_description: "Trenutno ne postoji ni jedan dostupan film",
        no_cartoon: "Nema dostupnih crtanih filmova",
        no_cartoon_description: "Trenutno ne postoji ni jedan dostupan crtani film",
        download_all: "Preuzmi",
        form: {
            select: "Selektuj",
            save: "Sačuvaj",
            saved: "Sačuvano",
            cancel: "Odustani"
        },
        download_path_modal: {
            title: 'Odaberite putanju za preuzimanje sadržaja',
            subtitle: 'Ovo je putanja na vašem uredjaju, gde će sadržaj biti preuzet',
        }
    },
    components: {
        content_download_bar: {
            all_downloads: "Pogledaj sve sadržaje",
            download_success_message: "Sadrzaj {title} je uspešno preuzet!"
        },
        download_button: {
            download_error_message: "Nismo mogli da pokrenemo preuzimanje sadržaja"
        },
        offline_mode_notification: {
            offline_message: "Aplikacija je zbog nestabilne konekcije prebačena u offline mod!"
        },
        pagination: {
            previous: "Prethodna",
            next: "Sledeća",
            first: "Prva",
            last: "Poslednja"
        },
        search_bar: {
            no_results: "Nije pronadjen rezultat za ",
            placeholder: "Pretraži serijale, video, knjige.."
        },
        search_modal: {
            title: "Pretraži"
        },
        suggestion_modal: {
            title: "Imate nešto na umu?",
            description: "Voleli bismo da čujemo tvoje predloge, sugestije za dodavanje sadržaja, ili jednostavno lepe reči."
        },
        nav_mode_switcher: {
            title: "App je {mode}"
        }
    },
    settings: {
        title: 'Opcije',
        description: 'Upravljaj svojim profilom i podešavanjima naloga',
        general: {
            title: 'Opšte',
            subtitle: 'Opšte Informacije',
            description: 'Ažuriraj opšte informacije o aplikaciji',
            dialog_video_title: 'Izaberi offline video plejer',
            dialog_book_title: 'Izaberi offline aplikaciju za otvaranje knjiga',
            content_per_page: 'Broj sadržaja po stranici'
        },
        profile: {
            title: 'Profil',
            subtitle: 'Informacije o profilu',
            description: 'Ažuriraj informacije o svom profilu',
            name: 'Ime',
            name_placeholder: 'Ime',
            email: 'Email',
            email_placeholder: 'Email',
        },
        downloads: {
            title: 'Preuzimanje Sadržaja',
            subtitle: 'Informacije o profilu',
            description: 'Ažuriraj informacije o svom profilu',
            manage_downloads: 'Preuzet sadržaj',
            manage_downloads_description: 'Upravljaj preuzetim sadržajem',
            download_path: 'Putanja gde se čuva preuzet sadržaj',
            video_quality: 'Video kvalitet',
            download_all_title: 'Preuzmi',
            download_all_description: 'Preuzmi celokupan sadržaj',
            delete_all_title: 'Obriši',
            delete_all_description: 'Obriši sav preuzet sadržaj',
            download_size_error: "Nema dovoljno prostora na disku! Potrebno je :size GB slobodnog prostora.",
            invalid_path_selected: "Nevažeća putanja za preuzimanje je postavljena u podešavanjima.",
            validation: {
                download_path: 'Morate odabrati validnu putanju za preuzimanje'
            },
            download_path_success: 'Putanja uspešno sačuvana'
        },
        password: {
            title: 'Ažuriranje Lozinke',
            subtitle: 'Ažurirajte vašu lozinku',
            description: 'Obezbedi da tvoj nalog koristi dugu i nasumičnu lozinku radi veće sigurnosti',
            current_password: 'Trenutna lozinka',
            new_password: 'Nova lozinka',
            confirm_password: 'Potvrdite lozinku',
            download_modal: {
                title: "Preuzmi sadržaj po kategoriji",
                description: "Izaberite katergorije iz koje želite da preuzmete sadržaj",
                total: 'Ukupno',
                download_selected: 'Preuzmi Sadržaj'
            },
            delete_modal: {
                title: "Brisanje svih fajlova",
                description: "Da li želite da obrišete sve preuzete fajlove?",
                submit: "Da"
            }
        },
        appearance: {
            title: 'Izgled',
            subtitle: 'Izgled Aplikacije',
            description: 'Izmenite izgled vaše aplikacije',
        }
    },
    download_manager: {
        cancel_all: "Zaustavi sve",
        file: "Ime fajla",
        progress: "Progress",
        size: "Veličina",
        status: "Status",
        action: "Akcija",
        no_active_downloads: "Nema aktivnih preuzimanja",
        no_active_downloads_description: "Trenutno nema aktivnih preuzimanja",
        could_not_start_download: "Preuzimanje fajla trenutno nije moguće",
        unknown_size: "Nepoznato",
        download_started: "Preuzimanje je započeto, prati progres na stranici za preuzimanje sadržaja",
        files_deleted: "Svi fajlovi su uspesno obrisani sa vašeg uredjaja",
    }
}
