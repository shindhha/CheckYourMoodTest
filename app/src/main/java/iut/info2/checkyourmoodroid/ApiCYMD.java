package iut.info2.checkyourmoodroid;

import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

public class ApiCYMD {

    public static final String API_URL = "http://172.20.0.2/CYMD/api/";

    private static String api_key = null;


    private static RequestQueue fileRequetes;

    private static RequestQueue getFileRequetes() {
        if (fileRequetes == null) {
            fileRequetes = Volley.newRequestQueue(MainActivity.getContext());
        }
        return fileRequetes;
    }


    public static void auth(String login, String password) {

        String url = API_URL + "login?login=" + login + "&password=" + password;
        System.out.println("URL DE LA DEMANDE : " + url);

        JsonObjectRequest request = new JsonObjectRequest(
                Request.Method.GET,
                url,
                null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        // On traite la réponse
                        try {
                            api_key = response.get("APIKEYDEMONAPPLI").toString();
                            System.out.println("API KEY : " + api_key);
                            MainActivity.getContext().authSuccess();

                        } catch (JSONException e) {
                            System.out.println("ERREUR : " + e.getMessage());
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(MainActivity.getContext(), "Erreur de connexion", Toast.LENGTH_SHORT).show();
                        System.out.println("ERREUR : " + error.getMessage());
                    }
                }
        );
        getFileRequetes().add(request);
        System.out.println("Requête envoyée");



    }
}