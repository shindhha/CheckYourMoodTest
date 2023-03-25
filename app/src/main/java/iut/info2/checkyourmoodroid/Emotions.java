package iut.info2.checkyourmoodroid;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.HashMap;

public class Emotions {

    private static HashMap<Integer, JSONObject> emotions = new HashMap<>();

    public static void loadEmotion(JSONArray emotions) {
        for (int i = 0; i < emotions.length(); i++) {
            try {
                JSONObject emotion = new JSONObject();
                emotion.put("nom", emotions.getJSONObject(i).getString("libelleHumeur"));
                emotion.put("emoji", emotions.getJSONObject(i).getString("emoji"));

                Emotions.emotions.put(emotions.getJSONObject(i).getInt("codeLibelle"), emotion);

            } catch (Exception e) {
                System.out.println("ERREUR : " + e.getMessage());
            }
        }
    }

    /**
     * Méthode permettant de récupérer une émotion à partir de son id
     * @param id L'id de l'émotion
     * @return L'émotion au format JSONObject (nom, emoji)
     */
    public static JSONObject getEmotion(int id) {
        return emotions.get(id);
    }

    public static HashMap<Integer, JSONObject> getEmotions() {
        return emotions;
    }

    public static int size() {
        return emotions.size();
    }
}
