<h2>Moje dovednosti</h2>
<ul>
    <?php foreach ($data['skills'] as $skill): ?>
        <li><?php echo htmlspecialchars($skill); ?></li>
    <?php endforeach; ?>
</ul>