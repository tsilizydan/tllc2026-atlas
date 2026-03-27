<!-- Partner List Print View -->
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900 border-b-2 border-gold-500 pb-2 inline-block">Partner Directory</h2>
</div>

<table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-gray-100 border-b border-gray-300">
            <th class="py-2 px-2 text-xs font-bold text-gray-700 uppercase">Company</th>
            <th class="py-2 px-2 text-xs font-bold text-gray-700 uppercase">Contact</th>
            <th class="py-2 px-2 text-xs font-bold text-gray-700 uppercase">Type</th>
            <th class="py-2 px-2 text-xs font-bold text-gray-700 uppercase">Email / Phone</th>
            <th class="py-2 px-2 text-xs font-bold text-gray-700 uppercase text-center">Status</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        <?php foreach ($partners ?? [] as $partner): ?>
        <tr>
            <td class="py-2 px-2 text-sm font-semibold text-gray-900"><?= e($partner['company_name']) ?></td>
            <td class="py-2 px-2 text-sm text-gray-600"><?= e($partner['contact_name']) ?></td>
            <td class="py-2 px-2 text-sm text-gray-600"><?= e($partner['partnership_type']) ?></td>
            <td class="py-2 px-2 text-sm text-gray-600">
                <div><?= e($partner['email']) ?></div>
                <div class="text-xs text-gray-500"><?= e($partner['phone']) ?></div>
            </td>
            <td class="py-2 px-2 text-center">
                <span class="inline-block px-2 py-0.5 rounded text-xs font-medium border <?= $partner['status'] === 'active' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-gray-50 border-gray-200 text-gray-600' ?>">
                    <?= ucfirst($partner['status']) ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="mt-8 text-xs text-gray-500 border-t pt-2">
    <p>Total Partners: <?= count($partners ?? []) ?></p>
</div>
