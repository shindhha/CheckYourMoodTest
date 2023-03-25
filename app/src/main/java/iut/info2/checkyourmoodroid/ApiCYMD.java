package iut.info2.checkyourmoodroid;

import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class ApiCYMD {

    public static final String API_URL = "http://172.20.0.2/CYMD/api/";

    private static String api_key = null;


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
                (VolleyError error) -> {
                    Toast.makeText(MainActivity.getContext(), "Erreur de connexion", Toast.LENGTH_SHORT).show();
                    System.out.println("ERREUR : " + error.getMessage());
                }
        );
    }


    /**
     * Méthode permettant de récupérer les informations de l'utilisateur
     */
    public static void getUserInfos() {

        String url = API_URL + "user";

        Map<String, String> header = new HashMap<>();
        header.put("APIKEYDEMONAPPLI", api_key);

        // ---- Obtention des informations de l'utilisateur ----
        Requests.simpleJSONObjectRequest(
                url,
                header,
                Request.Method.GET,
                (JSONObject response) -> {
                    MainActivity.getContext().displayUserInfos(response);
                },
                (VolleyError error) -> {
                    Toast.makeText(MainActivity.getContext(), "Erreur de connexion", Toast.LENGTH_SHORT).show();
                    System.out.println("ERREUR : " + error.getMessage());
                }
        );

        // ---- Obtention de la liste des émotions de la DB ----
        url = API_URL + "emotions";
        Requests.simpleJSONArrayRequest(
                url,
                header,
                Request.Method.GET,
                (JSONArray response) -> {
                    Emotions.loadEmotion(response);
                    MainActivity.getContext().loadSpinner();
                },
                (VolleyError error) -> {
                    Toast.makeText(MainActivity.getContext(), "Erreur de connexion", Toast.LENGTH_SHORT).show();
                    System.out.println("ERREUR : " + error.getMessage());
                }
        );


        // ---- Obtention des 5 dernières humeurs ----
        url = API_URL + "humeurs";
        Requests.simpleJSONArrayRequest(
                url,
                header,
                Request.Method.GET,
                (JSONArray response) -> {
                    MainActivity.getContext().displayHumeurs(response);
                },
                (VolleyError error) -> {
                    Toast.makeText(MainActivity.getContext(), "Erreur de connexion", Toast.LENGTH_SHORT).show();
                    System.out.println("ERREUR : " + error.getMessage());
                }
        );
    }
}