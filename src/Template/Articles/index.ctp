<h1>My Articles</h1>

<table>
    <tr>
        <th>Title</th>
        <th>Created</th>
    </tr>

    <?php foreach ($articles as $article) : ?>
    <tr>
        <td>
            <?= $this->HTML->link($article->title, ['action' => 'view', $article->slug]) ?>
        </td>

        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
    </tr>

    <?php endforeach; ?>

    
</table>