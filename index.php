<?php
require_once __DIR__ . '/../../src/Database.php';

$db = Database::connect();

$stmt = $db->query("
    SELECT
        t.*,
        ts.id AS tour_slot_id,
        ts.starts_at,
        ts.capacity,
        ts.meeting_point,
        ts.meeting_time,
        ts.departure_time,
        ts.dropoff_point,
        GREATEST(
            0,
            ts.capacity - COALESCE(SUM(
                CASE
                    WHEN b.booking_status = 'confirmed'
                    THEN b.guests
                    ELSE 0
                END
            ), 0)
        ) AS available
    FROM tours t
    LEFT JOIN tour_slots ts
        ON ts.tour_id = t.id
        AND ts.starts_at >= NOW()
    LEFT JOIN bookings b
        ON b.tour_slot_id = ts.id
    WHERE t.active = 1
    GROUP BY
        t.id,
        ts.id,
        ts.starts_at,
        ts.capacity,
        ts.meeting_point,
        ts.meeting_time,
        ts.departure_time,
        ts.dropoff_point
    ORDER BY t.id, ts.starts_at
");
$tours = $stmt->fetchAll();

$itinerariesByTour = [];
$tourIds = array_values(array_unique(array_column($tours, 'id')));
if ($tourIds) {
    $placeholders = implode(',', array_fill(0, count($tourIds), '?'));
    $itineraryStmt = $db->prepare("
        SELECT tour_id, title, time, description
        FROM tour_itinerary
        WHERE tour_id IN ({$placeholders})
        ORDER BY tour_id, sort_order, id
    ");
    $itineraryStmt->execute($tourIds);

    foreach ($itineraryStmt->fetchAll() as $item) {
        $itinerariesByTour[$item['tour_id']][] = $item;
    }
}

$lang = json_decode(file_get_contents(__DIR__ . '/lang.json'), true);
$commons = json_decode(file_get_contents(__DIR__ . '/../shared/commons.json'), true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-PCX46CCF"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
    </script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="/shared/styles.css" />
    <title>Osaka Private Experiences - Seasonal Tours</title>
    <style>
        .hero__seasonal{
            background-image: url('/shared/files/hero.webp');
            background-size: cover;
            background-position: center;
        }
        .hero-content{
            min-height: 90vh;
        }
        .seasonal-tour-dialog {
            height: 90vh;
        }
        .seasonal-tour-dialog .modal-content {
            max-height: 100%;
        }
    </style>
</head>

<body>
<?php include_once('inc/hero.php'); ?>
<?php include_once('inc/tours.php'); ?>
<?php include_once(__DIR__ . '/../shared/reviews.php'); ?>
<?php include_once('inc/aftercontent.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-booking-target]');
        if (!trigger) return;

        const target = document.querySelector(trigger.dataset.bookingTarget);
        if (!target) return;

        const currentModal = trigger.closest('.modal.show');
        if (currentModal) {
            currentModal.addEventListener('hidden.bs.modal', function () {
                bootstrap.Modal.getOrCreateInstance(target).show();
            }, { once: true });
            bootstrap.Modal.getOrCreateInstance(currentModal).hide();
            return;
        }

        bootstrap.Modal.getOrCreateInstance(target).show();
    });

    document.addEventListener('submit', async function (event) {
        const form = event.target.closest('[data-booking-form]');
        if (!form) return;

        event.preventDefault();
        const modal = form.closest('.modal');
        const submitButton = modal.querySelector('[data-booking-submit]');
        const errorMessage = modal.querySelector('[data-booking-error]');
        const successMessage = modal.querySelector('[data-booking-success]');
        const checkoutLink = modal.querySelector('[data-checkout-link]');
        const payload = Object.fromEntries(new FormData(form).entries());

        submitButton.disabled = true;
        submitButton.textContent = 'Saving…';
        errorMessage.hidden = true;

        try {
            const response = await fetch('/api/bookings.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.error || 'Unable to save your booking. Please try again.');
            }

            form.hidden = true;
            submitButton.hidden = true;
            successMessage.hidden = false;
            checkoutLink.hidden = false;
        } catch (error) {
            errorMessage.textContent = error.message || 'Unable to save your booking. Please try again.';
            errorMessage.hidden = false;
            submitButton.disabled = false;
            submitButton.textContent = 'Continue';
        }
    });
</script>
<?php include_once(__DIR__ . '/../shared/footer.php'); ?>
</body>
</html>