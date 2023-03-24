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

public class MainActivity extends AppCompatActivity {

    // Save the context
    private static MainActivity context;

    private Dialog popupLogin;

    public static MainActivity getContext() {
        return context;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Set the context
        context = this;


        // ===================== Chargement du spinner =====================


        Spinner spinner = (Spinner) findViewById(R.id.spinner_emotion);
        // Create an ArrayAdapter using the string array and a default spinner layout
        ArrayAdapter<CharSequence> adapter = ArrayAdapter.createFromResource(this,
                R.array.emotions_liste, android.R.layout.simple_spinner_item);
        // Specify the layout to use when the list of choices appears
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        // Apply the adapter to the spinner
        spinner.setAdapter(adapter);




        // ===================== Chargement du tableau =====================

        TableLayout table = findViewById(R.id.table_humeurs);

        TableRow.LayoutParams lp = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT);

        for (int i = 0; i < 5; i++) {
            TableRow row = new TableRow(this);
            row.setLayoutParams(lp);

            table.addView(row);
        }

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

    public void authSuccess() {
        System.out.println("Connexion réussie");

        // On cache la popup
        popupLogin.dismiss();

        // On affiche le tableau
        //TODO
    }
}