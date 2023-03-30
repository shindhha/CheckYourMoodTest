package iut.info2.checkyourmoodroid;

import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.VolleyError;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class ApiCYMD {

    public static final String API_URL = "http://10.0.2.2/CYMD/api/";

    private static String api_key = null;


    private static void gestionErreurBase(VolleyError error) {
        if (error.networkResponse == null) {
            Toast.makeText(MainActivity.getContext(), MainActivity.getContext().getString(R.string.toast_erreur_fatal), Toast.LENGTH_LONG).show();
        } else {
            try {
                JSONObject errorJSON = new JSONObject(new String(error.networkResponse.data));
                Toast.makeText(MainActivity.getContext(), MainActivity.getContext().getString(R.string.toast_erreur_generic, errorJSON.getString("message")), Toast.LENGTH_LONG).show();
            } catch (JSONException e) {
                // Impossible de récupérer le message d'erreur, on affiche un message par défaut
                Toast.makeText(MainActivity.getContext(), MainActivity.getContext().getString(R.string.toast_erreur_inconnue), Toast.LENGTH_SHORT).show();
            }
        }
    }


    /**
     * Méthode permettant de se connecter à l'API et de fermer la fenêtre de connexion
     * @param login Le login de l'utilisateur
     * @param password Le mot de passe de l'utilisateur
     */
    public static void auth(String login, String password) {

        String url = API_URL + "login?login=" + login + "&password=" + password;

        Requests.simpleJSONObjectRequest(
                url,
                null,
                null,
                Request.Method.GET,
                (JSONObject response) -> {
                    try {
                        api_key = response.get("APIKEYDEMONAPPLI").toString();
                        System.out.println("API KEY : " + api_key);
                        MainActivity.getContext().authSuccess();
                    } catch (JSONException e) {
                        System.out.println("ERREUR : " + e.getMessage());
                    }
                },
                ApiCYMD::gestionErreurBase
        );
    }


    /**
     * Méthode permettant de récupérer les informations de l'utilisateur + la liste des émotions
     */
    public static void getUserInfos() {

        String url = API_URL + "user";

        Map<String, String> header = new HashMap<>();
        header.put("APIKEYDEMONAPPLI", api_key);

        // ---- Obtention des informations de l'utilisateur ----
        Requests.simpleJSONObjectRequest(
                url,
                header,
                null,
                Request.Method.GET,
                (JSONObject response) -> {
                    MainActivity.getContext().displayUserInfos(response);
                },
                ApiCYMD::gestionErreurBase
        );

        // ---- Obtention de la liste des émotions de la DB ----
        url = API_URL + "emotions";
        Requests.simpleJSONArrayRequest(
                url,
                header,
                null,
                Request.Method.GET,
                (JSONArray response) -> {
                    Emotion.loadEmotions(response);
                    MainActivity.getContext().loadSpinnerHumeurs();

                    // Les émotions sont chargées, on peut maintenant obtenir les humeurs de l'utilisateur
                    getUserHumeurs();
                },
                ApiCYMD::gestionErreurBase
        );
    }

    /**
     * Méthode permettant d'obtenir les humeurs de l'utilisateur.
     * Séparé de getUserInfos() car elle nécessite que la liste des émotions soit chargée
     */
    public static void getUserHumeurs() {

        String url = API_URL + "humeurs";

        Map<String, String> header = new HashMap<>();
        header.put("APIKEYDEMONAPPLI", api_key);

        // ---- Obtention des 5 dernières humeurs ----
        Requests.simpleJSONArrayRequest(
                url,
                header,
                null,
                Request.Method.GET,
                (JSONArray response) -> {
                    MainActivity.getContext().displayHumeurs(response);
                },
                ApiCYMD::gestionErreurBase
        );
    }

    public static void postHumeur(
            int codeHumeur,
            String commentaire,
            String date,
            String heure
    ) {

        Map<String, String> header = new HashMap<>();
        header.put("APIKEYDEMONAPPLI", api_key);


        JSONObject body = new JSONObject();
        try {
            body.put("LIBELLE", codeHumeur);
            body.put("DATE_HUMEUR", date);
            body.put("HEURE", heure);
            body.put("CONTEXTE", commentaire);
        } catch (JSONException e) {
            System.out.println("ERREUR : " + e.getMessage());
        }

        String url = API_URL + "humeur";

        System.out.println("BODY : " + body);

        Requests.simpleJSONObjectRequest(
                url,
                header,
                body,
                Request.Method.POST,
                (JSONObject response) -> {
                    MainActivity.getContext().humeurPosted(true);
                },
                (VolleyError error) -> {
                    MainActivity.getContext().humeurPosted(false);
                    ApiCYMD.gestionErreurBase(error);
                }
        );
    }
}