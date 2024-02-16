// const cf7MakeHiddenFields = document.querySelectorAll('input[name="cf7-make-list-id"]')
// if (cf7MakeHiddenFields.length > 0) {
//     cf7MakeHiddenFields.forEach(field => {
//         field.value = 'sssssssssssssss'
//     })
// }

// allow only numbers in the phone field
const numberInputs = document.querySelectorAll('.wpcf7-tel');
if (numberInputs.length > 0) {
    numberInputs.forEach (numberInput => {
        addEventListener( 'input', (e) => {
           numberInput.value = numberInput.value.replace(/[^0-9]/g, '')
        })
    })
}

// add url and referral url values to contact form 7 corrsponding fields
let userRefUrls = document.querySelectorAll('input[name="cf7-url-ref"]');
let userUrls = document.querySelectorAll('input[name="cf7-url"]');
userRefUrls.forEach ( userRefUrl => {
userRefUrl.setAttribute("value", document.referrer);
})
userUrls.forEach ( userUrl => {
userUrl.setAttribute("value", document.URL);
});