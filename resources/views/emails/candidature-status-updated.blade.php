<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour du statut de votre candidature</title>
</head>
<body>
    <h1>Bonjour {{ $candidature->user->name }},</h1>
    <p>Nous vous informons que le statut de votre candidature pour le poste de "{{ $candidature->offre->titre }}" a été mis à jour.</p>
    <p>Le nouveau statut est : <strong>{{ $candidature->statut }}</strong>.</p>
    <p>Nous vous remercions pour votre intérêt et vous souhaitons bonne chance.</p>
    <p>Bien cordialement,<br>L'équipe de recrutement</p>
</body>
</html>
