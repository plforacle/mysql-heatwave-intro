<?php
require_once "config.php";

$query = "SELECT firstname, lastname, COUNT(booking.passenger_id) as count_bookings
          FROM passenger, booking
          WHERE booking.passenger_id = passenger.passenger_id
          AND (passenger.lastname = ? OR (passenger.firstname = ? AND passenger.lastname = ?))
          AND booking.price > ?
          GROUP BY firstname, lastname;";

if ($stmt = $link->prepare($query)) {
    $lastname = 'Aldrin';
    $firstname = 'Neil';
    $lastnameArmstrong = 'Armstrong';
    $price = 400.00;
    $stmt->bind_param("sssd", $lastname, $firstname, $lastnameArmstrong, $price);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Firstname</th>";
        echo "<th>Lastname</th>";
        echo "<th>Count</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['firstname'] . "</td>";
            echo "<td>" . $row['lastname'] . "</td>";
            echo "<td>" . $row['count_bookings'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No results found.";
    }

    $stmt->close();
} else {
    echo "Failed to prepare the query.";
}
?>