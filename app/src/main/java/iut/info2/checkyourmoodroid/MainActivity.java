package iut.info2.checkyourmoodroid;

import androidx.appcompat.app.AppCompatActivity;

import android.app.Dialog;
import android.os.Bundle;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

public class MainActivity extends AppCompatActivity {

    // Save the context
    private static MainActivity context;

    private Dialog popupLogin;

    private TableLayout tableHumeurs;

    private Spinner spinnerEmotion;

    private TextView dateHeure;

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

        // ===================== Affichage de la popup de connexion =====================

        popupLogin = new Dialog(this);
        popupLogin.setContentView(R.layout.popup_login);
        popupLogin.setCanceledOnTouchOutside(false);
        popupLogin.setCancelable(false);
        popupLogin.getWindow().setLayout(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT);
        popupLogin.show();

        Button btnConnexion = popupLogin.findViewById(R.id.btnConnexion);
        EditText txtLogin = popupLogin.findViewById(R.id.input_identifiant);
        EditText txtPassword = popupLogin.findViewById(R.id.input_mdp);

        btnConnexion.setOnClickListener(view1 -> {
            String identifiant = txtLogin.getText().toString().trim();
            String mdp = txtPassword.getText().toString().trim();

            if (identifiant.length() == 0 || mdp.length() == 0) {
                //TODO TOAST ERREUR
                System.out.println("ERREUR, identifiant ou mot de passe vide");
            } else {
                System.out.println("Connexion en cours...");
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
        popupLogin.dismiss();

        // On mets a jour le champ de la date et heure
        dateHeure.setText(getString(R.string.date_heure, DateFormatter.getTime()));

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
                icon.setText(Emotions.getEmotion(idEmotion).getString("emoji"));
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
    public void loadSpinner() {

        List<String> emotionsList = new ArrayList<>();
        int i = 0;

        Emotions.getEmotions().forEach((id, jsonObj) -> {
            String libelle;
            try {
                libelle = jsonObj.getString("emoji") + " - " + jsonObj.getString("nom");
            } catch (JSONException e) {
                libelle = "Erreur de chargement de l'émotion";
            }

            emotionsList.add(libelle);
        });

        String[] emotionsArray = emotionsList.toArray(new String[0]);

        ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, emotionsArray);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

        spinnerEmotion.setAdapter(adapter);
    }
}