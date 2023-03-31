package iut.info2.checkyourmoodroid;

import android.util.Log;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.HurlStack;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONObject;

import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.security.KeyManagementException;
import java.security.KeyStore;
import java.security.KeyStoreException;
import java.security.NoSuchAlgorithmException;
import java.security.cert.CertificateException;
import java.security.cert.X509Certificate;
import java.util.HashMap;
import java.util.Map;
import java.util.function.Consumer;

import javax.net.ssl.HostnameVerifier;
import javax.net.ssl.HttpsURLConnection;
import javax.net.ssl.SSLContext;
import javax.net.ssl.SSLSession;
import javax.net.ssl.SSLSocketFactory;
import javax.net.ssl.TrustManager;
import javax.net.ssl.TrustManagerFactory;
import javax.net.ssl.X509TrustManager;

public class Requests {

    private static RequestQueue fileRequetes;


    // ------ Gestion des certificats ssl auto-signés ------
    static HurlStack hurlStack = new HurlStack() {
        @Override
        protected HttpURLConnection createConnection(URL url) throws IOException {
            HttpsURLConnection httpsURLConnection = (HttpsURLConnection) super.createConnection(url);
            try {
                httpsURLConnection.setSSLSocketFactory(getSSLSocketFactory());
                httpsURLConnection.setHostnameVerifier(getHostnameVerifier());
            } catch (Exception e) {
                e.printStackTrace();
            }
            return httpsURLConnection;
        }
    };


    private static HostnameVerifier getHostnameVerifier() {
        return new HostnameVerifier() {
            @Override
            public boolean verify(String hostname, SSLSession session) {
                return true; // Permet d'accepter les certificats ssl auto-signés
            }
        };
    }

    private static TrustManager[] getWrappedTrustManagers(TrustManager[] trustManagers) {
        final X509TrustManager originalTrustManager = (X509TrustManager) trustManagers[0];
        return new TrustManager[]{
                new X509TrustManager() {
                    public X509Certificate[] getAcceptedIssuers() {
                        return originalTrustManager.getAcceptedIssuers();
                    }

                    public void checkClientTrusted(X509Certificate[] certs, String authType) {
                        try {
                            if (certs != null && certs.length > 0){
                                certs[0].checkValidity();
                            } else {
                                originalTrustManager.checkClientTrusted(certs, authType);
                            }
                        } catch (CertificateException e) {
                            System.out.println("checkClientTrusted " + e);
                        }
                    }

                    public void checkServerTrusted(X509Certificate[] certs, String authType) {
                        try {
                            if (certs != null && certs.length > 0){
                                certs[0].checkValidity();
                            } else {
                                originalTrustManager.checkServerTrusted(certs, authType);
                            }
                        } catch (CertificateException e) {
                            Log.w("checkServerTrusted", e.toString());
                        }
                    }
                }
        };
    }

    private static SSLSocketFactory getSSLSocketFactory()
            throws CertificateException, KeyStoreException, IOException, NoSuchAlgorithmException, KeyManagementException {
        KeyStore keyStore = KeyStore.getInstance("BKS");
        keyStore.load(null, null);

        String tmfAlgorithm = TrustManagerFactory.getDefaultAlgorithm();
        TrustManagerFactory tmf = TrustManagerFactory.getInstance(tmfAlgorithm);
        tmf.init(keyStore);

        TrustManager[] wrappedTrustManagers = getWrappedTrustManagers(tmf.getTrustManagers());

        SSLContext sslContext = SSLContext.getInstance("TLS");
        sslContext.init(null, wrappedTrustManagers, null);

        return sslContext.getSocketFactory();
    }



    private static RequestQueue getFileRequetes() {
        if (fileRequetes == null) {
            // On regarde si on doi prendre en charge les certificats ssl avec https
            if (ApiCYMD.API_URL.startsWith("https")) {
                fileRequetes = Volley.newRequestQueue(MainActivity.getContext(), hurlStack);
            } else {
                fileRequetes = Volley.newRequestQueue(MainActivity.getContext());
            }
        }
        return fileRequetes;
    }

    // ----- Fin gestion des certificats ssl auto-signés -----


    /**
     * Méthode permettant de faire une requête simple à une url et récupérer un JSONObject
     * @param url L'url de la requête
     * @param header Les headers de la requête
     * @param method La méthode de la requête (Voir {@link Request.Method}})
     * @param processResponse La fonction à appeler en cas de succès
     * @param processError La fonction à appeler en cas d'erreur
     */
    public static void simpleJSONObjectRequest(String url,
                                               Map<String, String> header,
                                               JSONObject body,
                                               int method,
                                               Consumer<JSONObject> processResponse,
                                               Consumer<VolleyError> processError) {

        // Création de la requête
        JsonObjectRequest request = new JsonObjectRequest(
                method,
                url,
                body,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        processResponse.accept(response);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        processError.accept(error);
                    }
                }
        ) {
            // Headers
            @Override
            public Map<String, String> getHeaders() {
                if (header == null) {
                    return new HashMap<>();
                } else {
                    return header;
                }
            }
        };

        // Ajout de la requête à la file
        getFileRequetes().add(request);
    }


    /**
     * Méthode permettant de faire une requête simple à une url et de récupérer un tableau JSON
     * @param url L'url de la requête
     * @param header Les headers de la requête
     * @param method La méthode de la requête (Voir {@link Request.Method}})
     * @param processResponse La fonction à appeler en cas de succès
     * @param processError La fonction à appeler en cas d'erreur
     */
    public static void simpleJSONArrayRequest(String url,
                                              Map<String, String> header,
                                              JSONArray body,
                                              int method,
                                              Consumer<JSONArray> processResponse,
                                              Consumer<VolleyError> processError) {

        // Création de la requête
        JsonArrayRequest request = new JsonArrayRequest(
                method,
                url,
                body,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        processResponse.accept(response);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        processError.accept(error);
                    }
                }
        ) {
            // Headers
            @Override
            public Map<String, String> getHeaders() {
                if (header == null) {
                    return new HashMap<>();
                } else {
                    return header;
                }
            }
        };

        // Ajout de la requête à la file
        getFileRequetes().add(request);
    }
}