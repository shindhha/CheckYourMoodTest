package iut.info2.checkyourmoodroid;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Locale;

public class DateFormatter {

    private static final SimpleDateFormat SDF_FR = new SimpleDateFormat("dd/MM/yyyy - HH:mm", Locale.FRANCE);

    private static final SimpleDateFormat SDF_EN_DATE = new SimpleDateFormat("yyyy-MM-dd", Locale.ENGLISH);

    private static final SimpleDateFormat SDF_EN_TIME = new SimpleDateFormat("HH:mm:ss", Locale.ENGLISH);

    public static String formatDate(String date) {
        String[] dateSplit = date.split("-");
        return dateSplit[2] + "/" + dateSplit[1] + "/" + dateSplit[0];
    }

    public static String formatTime(String time) {
        String[] timeSplit = time.split(":");
        return timeSplit[0] + "h" + timeSplit[1];
    }

    public static String formatDateTime(String date, String time) {
        return "Le " + formatDate(date) + " à " + formatTime(time);
    }

    public static String getCurrentTime() {
        Calendar calendar = Calendar.getInstance();
        return SDF_FR.format(calendar.getTime());
    }

    public static String getTime(Calendar date) {
        return SDF_FR.format(date.getTime());
    }

    public static String getApiDate(Calendar date) {
        return SDF_EN_DATE.format(date.getTime());
    }

    public static String getApiTime(Calendar date) {
        return SDF_EN_TIME.format(date.getTime());
    }
}
