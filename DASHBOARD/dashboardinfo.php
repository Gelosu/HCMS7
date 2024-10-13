<section id="dashboard" class="section">
    <h2>DASHBOARD</h2>
    
    <div class="card-container">
        <div class="card">
            <h3>Total Registered Patients</h3>
            <p id="total-patients">Loading...</p>
        </div>
        <div class="card">
            <h3>Total Medicines</h3>
            <p id="total-medicines">Loading...</p>
        </div>
        <div class="card">
            <h3>Appointments Today</h3>
            <p id="appointments-today">Loading...</p>
        </div>
        <div class="card">
            <h3>Total Medical Supplies</h3>
            <p id="total-medications">Loading...</p>
        </div>
    </div>

    <!-- Upcoming Events Section -->
    <div id="upcoming-events" class="section">
        <h2>Upcoming Events</h2>
        <div id="events-carousel" class="carousel">
            <?php
            include 'connect.php';

            // Get current date and date 7 days from now
            $currentDate = new DateTime();
            $endDate = new DateTime();
            $endDate->modify('+7 days');

            // Format the dates and store them in variables
            $currentDateFormatted = $currentDate->format('Y-m-d H:i:s');
            $endDateFormatted = $endDate->format('Y-m-d H:i:s');

            // Fetch upcoming events from the database
            $sql = "SELECT * FROM events WHERE datetime BETWEEN ? AND ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $currentDateFormatted, $endDateFormatted); // Pass variables
            $stmt->execute();
            $result = $stmt->get_result();

            // Store events
            $events = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $events[] = $row;
                }
            }
            $conn->close();
            ?>
        </div>
        <p id="no-events-message" style="display: none;">Events coming soon...</p>
    </div>
</section>