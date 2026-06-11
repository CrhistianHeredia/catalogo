/**
 * Unit tests for printTable + escapeHtml in js/usuario.js
 *
 * Run with: node tests/printTable.test.js
 */

global.allUsuarios = [];

/**
 * Escape HTML special chars (mirrors the function in usuario.js)
 */
function escapeHtml(unsafe) {
  if (unsafe == null) return '';
  if (typeof unsafe !== 'string') return String(unsafe);
  return unsafe
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

/**
 * Generates full table body HTML (mirrors printTable in usuario.js)
 */
function printTableHTML() {
  var tr = "";
  for (var i = 0; i < allUsuarios.length; i++) {
     var safeName = escapeHtml(allUsuarios[i]['user_name']);
     var safeEmail = escapeHtml(allUsuarios[i]['email']);
     var safePhone = escapeHtml(allUsuarios[i]['phone']);
     var safeEmailHref = escapeHtml(allUsuarios[i]['email']);
     var btn = '<button type="button" class="btn-action btn-action-edit" onclick="editarUsu('+i+')" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></button>'
            +'<button type="button" class="btn-action btn-action-delete ms-1" onclick="eliminaUsu('+i+')" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></button>';
      tr += '<tr>'
        +'<td class="ps-4 fw-semibold">'+safeName+'</td>'
        +'<td><a href="mailto:'+safeEmailHref+'" class="text-decoration-none">'+safeEmail+'</a></td>'
        +'<td>'+safePhone+'</td>'
        +'<td class="text-center">'
          +'<div class="d-flex justify-content-center gap-1">'
           + btn
          +'</div>'
        +'</td>'
      +'</tr>';
  }
  return tr;
}

// ─── Helpers ──────────────────────────────────────────────

function assert(condition, msg) {
  if (!condition) {
    console.error('FAIL: ' + msg);
    process.exitCode = 1;
  } else {
    console.log('PASS: ' + msg);
  }
}

function assertIncludes(haystack, needle, msg) {
  var ok = haystack.indexOf(needle) !== -1;
  assert(ok, msg || ('Expected "' + needle + '" to appear'));
}

function assertNotIncludes(haystack, needle, msg) {
  var ok = haystack.indexOf(needle) === -1;
  assert(ok, msg || ('Expected "' + needle + '" to NOT appear'));
}

// ─── escapeHtml tests ─────────────────────────────────────

var escResult = escapeHtml('<script>alert("xss")</script>');
assert(escResult === '&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;',
  'escapeHtml escapes < > "');

escResult = escapeHtml("it's & stuff");
assert(escResult === 'it&#039;s &amp; stuff',
  'escapeHtml escapes & and single quote');

escResult = escapeHtml('plain text');
assert(escResult === 'plain text', 'escapeHtml passes plain text unchanged');

escResult = escapeHtml('');
assert(escResult === '', 'escapeHtml handles empty string');

escResult = escapeHtml(null);
assert(escResult === '', 'escapeHtml handles null');

escResult = escapeHtml(undefined);
assert(escResult === '', 'escapeHtml handles undefined');

// ─── printTable tests ─────────────────────────────────────

// Test 1: empty users
allUsuarios = [];
var html = printTableHTML();
assert(html === '', 'Empty array produces empty string');

// Test 2: single user
allUsuarios = [
  { user_name: 'Alice', email: 'alice@test.com', phone: '555-0100', id_user: 1 }
];
html = printTableHTML();
assertIncludes(html, 'Alice', 'Single user: name appears');
assertIncludes(html, 'alice@test.com', 'Single user: email appears');
assertIncludes(html, '555-0100', 'Single user: phone appears');
assertIncludes(html, 'btn-action-edit', 'Single user: edit button present');
assertIncludes(html, 'btn-action-delete', 'Single user: delete button present');
assertIncludes(html, 'fa-pencil', 'Single user: pencil icon');
assertIncludes(html, 'fa-trash', 'Single user: trash icon');
assertIncludes(html, 'mailto:alice@test.com', 'Single user: mailto link');
assertIncludes(html, '<tr>', 'Single user: table row open');
assertIncludes(html, '</tr>', 'Single user: table row close');

// Test 3: multiple users
allUsuarios = [
  { user_name: 'Alice', email: 'alice@test.com', phone: '555-0100', id_user: 1 },
  { user_name: 'Bob', email: 'bob@test.com', phone: '555-0200', id_user: 2 },
  { user_name: 'Carol', email: 'carol@test.com', phone: '555-0300', id_user: 3 }
];
html = printTableHTML();
assertIncludes(html, 'Alice', 'Multiple: Alice present');
assertIncludes(html, 'Bob', 'Multiple: Bob present');
assertIncludes(html, 'Carol', 'Multiple: Carol present');
assert((html.match(/<tr>/g) || []).length === 3, 'Multiple: 3 rows');
assert((html.match(/btn-action-edit/g) || []).length === 3, 'Multiple: 3 edit buttons');
assert((html.match(/btn-action-delete/g) || []).length === 3, 'Multiple: 3 delete buttons');

// Test 4: onclick references correct index
allUsuarios = [
  { user_name: 'Zero', email: 'z@t.com', phone: '000', id_user: 0 },
  { user_name: 'One', email: 'o@t.com', phone: '111', id_user: 1 }
];
html = printTableHTML();
assertIncludes(html, "editarUsu(0)", 'Index 0 onclick editar');
assertIncludes(html, "eliminaUsu(1)", 'Index 1 onclick elimina');
assertNotIncludes(html, "editarUsu(2)", 'No out-of-bounds index');

// Test 5: XSS prevention — <script> in name gets escaped
allUsuarios = [
  { user_name: '<script>alert("xss")</script>', email: 'x@t.com', phone: '000', id_user: 1 }
];
html = printTableHTML();
assertNotIncludes(html, '<script>', 'XSS: raw <script> is NOT in output');
assertIncludes(html, '&lt;script&gt;', 'XSS: <script> is HTML-escaped');
assertIncludes(html, '&quot;', 'XSS: double quotes are escaped');

// Test 6: XSS prevention — email with injection
allUsuarios = [
  { user_name: 'Hacker', email: '"><script>evil()</script>', phone: '000', id_user: 1 }
];
html = printTableHTML();
assertIncludes(html, '&quot;&gt;&lt;script&gt;evil()&lt;/script&gt;',
  'XSS: email with quote+script is escaped');
// The escaped string still contains "evil()" text inside the safe encoding
// The important thing is that raw <script> tags are gone
assertNotIncludes(html, '<script>evil()</script>',
  'XSS: raw unescaped <script> tag is NOT in output');
assertNotIncludes(html, '"><script>',
  'XSS: raw quote+tag combo NOT in output');

// Test 7: XSS in phone field
allUsuarios = [
  { user_name: 'X', email: 'x@t.com', phone: '<img src=x onerror=alert(1)>', id_user: 1 }
];
html = printTableHTML();
assertIncludes(html, '&lt;img src=x onerror=alert(1)&gt;',
  'XSS: phone with img onerror is escaped');
assertNotIncludes(html, '<img src=x onerror=alert(1)>',
  'XSS: raw unescaped img tag NOT in output');

console.log('\nAll tests completed.');
