package iut.info2.checkyourmoodroid;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Locale;

public class DateFormatter {

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

    public static String getTime() {
        Calendar calendar = Calendar.getInstance();
        SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy - HH:mm:ss", Locale.FRANCE);

        return sdf.format(calendar.getTime());
    }
}
