package iut.info2.checkyourmoodroid;

import androidx.appcompat.app.AppCompatActivity;

import android.app.Dialog;
import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;

public class MainActivity extends AppCompatActivity {

    // Save the context
    private static MainActivity context;

    private Dialog popupDateHeure;

    private TableLayout tableHumeurs;

    private Spinner spinnerEmotion;

    private TextView dateHeure;

    private Calendar date;

    public static MainActivity getContext() {
        return context;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Set the context
        context = this;

        tableHumeurs = findViewById(R.id.table_humeurs);
        spinnerEmotion = findViewById(R.id.spinner_emotion);
        dateHeure = findViewById(R.id.texte_date_heure);
        date = Calendar.getInstance();

        Button btnChangerdateHeure = findViewById(R.id.btn_changer_date_heure);
        btnChangerdateHeure.setOnClickListener(this::dateHeurePopup);

        Button btnAjouterHumeur = findViewById(R.id.btn_poster_humeur);
        btnAjouterHumeur.setOnClickListener(this::posterHumeur);

        Button btnRafraichir = findViewById(R.id.btn_rafraichir);
        btnRafraichir.setOnClickListener((View view) -> {
            ApiCYMD.getUserHumeurs();
        });

        // ===================== Affichage de la popup de connexion =====================

        popupDateHeure = new Dialog(this);
        popupDateHeure.setContentView(R.layout.popup_login);
        popupDateHeure.setCanceledOnTouchOutside(false);
        popupDateHeure.setCancelable(false);
        popupDateHeure.getWindow().setLayout(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT);
        popupDateHeure.show();

        Button btnConnexion = popupDateHeure.findViewById(R.id.btnConnexion);
        EditText txtLogin = popupDateHeure.findViewById(R.id.input_identifiant);
        EditText txtPassword = popupDateHeure.findViewById(R.id.input_mdp);

        btnConnexion.setOnClickListener(view1 -> {
            String identifiant = txtLogin.getText().toString().trim();
            String mdp = txtPassword.getText().toString().trim();

            if (identifiant.length() == 0 || mdp.length() == 0) {
                Toast.makeText(context, getString(R.string.toast_erreur_creds_vide), Toast.LENGTH_SHORT).show();
            } else {
                ApiCYMD.auth(identifiant, mdp);
            }

        });
    }

    /**
     * Méthode permattant de fermer la fenêtre de connexion et d'initialiser la vue avec
     * les informations relatives à l'utilisateur
     */
    public void authSuccess() {
        System.out.println("Connexion réussie");

        // On cache la popup
        popupDateHeure.dismiss();

        // On mets a jour le champ de la date et heure
        dateHeure.setText(getString(R.string.date_heure, DateFormatter.getCurrentTime()));

        // On affiche les infos relatives a l'utilisateur
        ApiCYMD.getUserInfos();
    }


    public void displayUserInfos(JSONObject infos) {
        String utilisateur = "";
        try {
            utilisateur = infos.getString("prenom") + " " + infos.getString("nom");
        } catch (JSONException e) {
            e.printStackTrace();
        }

        ((TextView) findViewById(R.id.texte_bonjour))
                .setText(getString(R.string.bonjour, utilisateur));
    }


    /**
     * Méthode permettant d'afficher le tableau des humeurs de l'utilisateur à partir
     * du JSONArray reçu par l'API
     * @param humeurs Le JSONArray contenant les humeurs de l'utilisateur
     */
    public void displayHumeurs(JSONArray humeurs) {
        System.out.println("Affichage des humeurs");

        // Clear the table
        tableHumeurs.removeAllViews();

        TableRow.LayoutParams lp = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT);
        lp.setMargins(5, 0, 5, 0);

        // Boucle sur tous les objets du tableau json
        try {
            for (int i = 0; i < humeurs.length(); i++) {
                JSONObject humeur = humeurs.getJSONObject(i);

                TableRow row = new TableRow(this);
                row.setLayoutParams(lp);

                TextView icon = new TextView(this);
                int idEmotion = humeur.getInt("libelle");
                icon.setText(Emotion.getEmotionFromId(idEmotion).getEmoji());
                icon.setLayoutParams(lp);

                TextView date = new TextView(this);
                date.setText(DateFormatter.formatDateTime(humeur.getString("dateHumeur"), humeur.getString("heure")));
                date.setLayoutParams(lp);

                TextView commentaire = new TextView(this);
                if (!humeur.getString("contexte").equals("null")) {
                    commentaire.setText(humeur.getString("contexte").trim());
                }
                commentaire.setLayoutParams(lp);

                row.addView(icon);
                row.addView(date);
                row.addView(commentaire);

                tableHumeurs.addView(row);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }


    /**
     * Méthode permettant de charger le spinner avec les émotions obtenus par l'API
     */
    public void loadSpinnerHumeurs() {

        List<String> emotionsList = new ArrayList<>();
        int i = 0;

        Emotion.getEmotions().forEach((emotion) -> {
            emotionsList.add(emotion.toString());
        });

        String[] emotionsArray = emotionsList.toArray(new String[0]);

        ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, emotionsArray);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

        spinnerEmotion.setAdapter(adapter);
    }

    /**
     * Méthode permettant d'afficher la popup de changement de date et heure
     * @param v La vue qui a appelé la méthode
     */
    public void dateHeurePopup(View v) {
        // --- Initialisation de la popup ---
        popupDateHeure = new Dialog(this);
        popupDateHeure.setContentView(R.layout.popup_date_heure);
        popupDateHeure.setCanceledOnTouchOutside(true);
        popupDateHeure.setCancelable(true);
        popupDateHeure.getWindow().setLayout(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);

        // --- Initialisation des composants ---
        Button btnValider = popupDateHeure.findViewById(R.id.valider);
        DatePicker datePicker = popupDateHeure.findViewById(R.id.datePicker);
        TimePicker heurePicker = popupDateHeure.findViewById(R.id.timePicker);

        datePicker.setMinDate(Calendar.getInstance().getTimeInMillis() - 1000 * 60 * 60 * 24);
        datePicker.setMaxDate(Calendar.getInstance().getTimeInMillis());

        heurePicker.setIs24HourView(true);

        // --- Initialisation des listeners ---
        btnValider.setOnClickListener(view -> {
            Calendar dateSelec = Calendar.getInstance();
            dateSelec.set(datePicker.getYear(), datePicker.getMonth(), datePicker.getDayOfMonth(),
                    heurePicker.getHour(), heurePicker.getMinute(), 0);

            if (dateSelec.getTimeInMillis() > Calendar.getInstance().getTimeInMillis() || dateSelec.getTimeInMillis() < Calendar.getInstance().getTimeInMillis() - 1000 * 60 * 60 * 24) {
                Toast.makeText(context, getString(R.string.toast_erreur_date_heure_invalide), Toast.LENGTH_LONG).show();
            } else {
                dateHeure.setText(getString(R.string.date_heure, DateFormatter.getTime(dateSelec)));
                popupDateHeure.dismiss();
                date.setTimeInMillis(dateSelec.getTimeInMillis());
            }
        });

        popupDateHeure.show();
    }


    /**
     * Méthode permettant de poster une humeur sur l'api
     * @param v La vue qui a appelé la méthode
     */
    public void posterHumeur(View v) {
        // On désactive le bouton pour éviter les doublons
        findViewById(R.id.btn_poster_humeur).setEnabled(false);

        // Récupérations des informations
        String commentaire = ((EditText) findViewById(R.id.description_humeur)).getText().toString().trim();

        int idEmotion = ((Spinner) findViewById(R.id.spinner_emotion)).getSelectedItemPosition();
        idEmotion = Emotion.getIdFromEmotion(Emotion.getEmotions().get(idEmotion));

        String dateStr = DateFormatter.getApiDate(date);
        String heureStr = DateFormatter.getApiTime(date);

        // Envoi de la requête
        ApiCYMD.postHumeur(idEmotion, commentaire, dateStr, heureStr);
    }

    /**
     * Méthode appelée lorsque l'utilisateur a posté une humeur
     * @param success Si la requête a réussi ou non
     */
    public void humeurPosted(boolean success) {

        // On réactive le bouton
        findViewById(R.id.btn_poster_humeur).setEnabled(true);

        if (!success) {
            // On affiche un message d'erreur
            Toast.makeText(this, "Erreur lors de la publication de l'humeur", Toast.LENGTH_LONG).show();
        } else {
            // On vide les champs
            ((EditText) findViewById(R.id.description_humeur)).setText("");
            date = Calendar.getInstance();
            dateHeure.setText(getString(R.string.date_heure, DateFormatter.getTime(date)));

            // On affiche un message de succès
            Toast.makeText(this, "Humeur postée avec succès !", Toast.LENGTH_LONG).show();

            // On recharge la liste des humeurs pour afficher la nouvelle
            ApiCYMD.getUserHumeurs();
        }
    }
}