<?php
include("database.php");
require_once('vendor/autoload.php');
require_once('stripe-php/init.php');

// Récupérer le contenu de la requête webhook
$payload = @file_get_contents('php://input');
$event = null;

// Vérifier si le payload a été récupéré correctement
if ($payload !== false) {
    // Décoder le payload JSON en un objet événement
    $event = json_decode($payload);

    // Traiter l'événement selon son type
    if ($event->type === 'payment_intent.succeeded') {
        // L'événement correspond à un paiement confirmé

        // Récupérer les informations pertinentes de l'événement
        $paymentIntent = $event->data->object;
        $paymentIntentId = $paymentIntent->id;

        // Récupérer les métadonnées du paiement (contenant l'ID de l'utilisateur et l'ID de l'article)
        $metadata = $paymentIntent->metadata;
        $userId = $metadata->user_id;
        $articleId = $metadata->article_id;

        // Insérer l'achat dans la table de liaison user_purchases
        $query = $db->prepare("INSERT INTO user_purchases (user_id, article_id) VALUES (:user_id, :article_id)");
        $query->bindParam(':user_id', $userId);
        $query->bindParam(':article_id', $articleId);
        $query->execute();
    }
}

// Répondre à l'événement avec un statut HTTP 200 pour indiquer à Stripe que l'événement a été traité avec succès
http_response_code(200);
?>