package iut.info2.checkyourmoodroid;

import androidx.annotation.NonNull;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Emotion {

    private static final List<Emotion> emotions = new ArrayList<>();

    private static final Map<Integer, Emotion> idToEmotion = new HashMap<>();

    public static void loadEmotions(JSONArray emotions) {
        Emotion.emotions.clear();
        for (int i = 0; i < emotions.length(); i++) {
            try {
                Emotion emotionObj = new Emotion(emotions.getJSONObject(i));
                Emotion.emotions.add(emotionObj);
                Emotion.idToEmotion.put(emotions.getJSONObject(i).getInt("codeLibelle"), emotionObj);
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    public static List<Emotion> getEmotions() {
        return emotions;
    }

    public static Emotion getEmotionFromId(int id) {
        return idToEmotion.get(id);
    }

    public static int getIdFromEmotion(Emotion emotion) {
        for (Map.Entry<Integer, Emotion> entry : idToEmotion.entrySet()) {
            if (entry.getValue().equals(emotion)) {
                return entry.getKey();
            }
        }
        return -1;
    }

    // -------------------------------------------------------

    private final String emoji;
    private final String libelle;

    public Emotion(JSONObject emotion) throws Exception {
        this.emoji = emotion.getString("emoji");
        this.libelle = emotion.getString("libelleHumeur");
    }

    public String getEmoji() {
        return emoji;
    }

    public String getLibelle() {
        return libelle;
    }

    @NonNull
    @Override
    public String toString() {
        return getEmoji() + " - " + getLibelle();
    }


}
