<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the list of symptoms along with their search keywords as arrays
        $symptoms = [
            // General
            ['name' => 'Fever', 'keywords' => ['high temperature', 'pyrexia', 'body heat', 'hot body']],
            ['name' => 'Chills', 'keywords' => ['shivering', 'feeling cold', 'shaking']],
            ['name' => 'Fatigue', 'keywords' => ['tiredness', 'exhaustion', 'low energy', 'lethargy']],
            ['name' => 'Weakness', 'keywords' => ['loss of strength', 'lack of energy', 'faintness']],
            ['name' => 'Weight Loss', 'keywords' => ['losing weight', 'dropping pounds', 'sudden thinness']],
            ['name' => 'Weight Gain', 'keywords' => ['gaining weight', 'putting on pounds', 'sudden swelling']],
            ['name' => 'Loss of Appetite', 'keywords' => ['no appetite', 'not hungry', 'poor eating', 'anorexia']],
            ['name' => 'Excessive Sweating', 'keywords' => ['hyperhidrosis', 'heavy sweating', 'profuse sweat']],
            ['name' => 'Night Sweats', 'keywords' => ['sweating at night', 'soaked sheets', 'nocturnal hyperhidrosis']],

            // Head & Neurological
            ['name' => 'Headache', 'keywords' => ['head pain', 'head ache', 'cranial pain']],
            ['name' => 'Migraine', 'keywords' => ['severe headache', 'throbbing head pain', 'one-sided headache']],
            ['name' => 'Dizziness', 'keywords' => ['lightheadedness', 'unsteadiness', 'wooziness']],
            ['name' => 'Vertigo', 'keywords' => ['spinning sensation', 'room spinning', 'balance loss']],
            ['name' => 'Fainting', 'keywords' => ['syncope', 'passing out', 'blacking out']],
            ['name' => 'Seizure', 'keywords' => ['fit', 'convulsion', 'epileptic attack']],
            ['name' => 'Memory Loss', 'keywords' => ['forgetfulness', 'amnesia', 'poor memory']],
            ['name' => 'Confusion', 'keywords' => ['disorientation', 'mental fog', 'inability to think clearly']],
            ['name' => 'Insomnia', 'keywords' => ['sleeplessness', 'trouble sleeping', 'wakefulness']],
            ['name' => 'Sleepiness', 'keywords' => ['drowsiness', 'somnolence', 'feeling sleepy']],
            ['name' => 'Tremor', 'keywords' => ['shaking', 'trembling hands', 'tremor in limbs']],
            ['name' => 'Numbness', 'keywords' => ['loss of feeling', 'lack of sensation', 'dead feeling']],
            ['name' => 'Tingling Sensation', 'keywords' => ['pins and needles', 'paresthesia', 'prickling']],

            // Eye
            ['name' => 'Eye Pain', 'keywords' => ['sore eyes', 'aching eyes', 'painful eyes']],
            ['name' => 'Red Eye', 'keywords' => ['bloodshot eyes', 'pink eye', 'conjunctivitis']],
            ['name' => 'Blurred Vision', 'keywords' => ['unclear sight', 'blurry vision', 'fuzzy vision']],
            ['name' => 'Double Vision', 'keywords' => ['diplopia', 'seeing two images']],
            ['name' => 'Watery Eyes', 'keywords' => ['tearing eyes', 'excessive tears', 'epiphora']],
            ['name' => 'Dry Eyes', 'keywords' => ['scratchy eyes', 'lack of tears', 'irritated eyes']],

            // Ear
            ['name' => 'Ear Pain', 'keywords' => ['earache', 'sore ear', 'otalgia']],
            ['name' => 'Hearing Loss', 'keywords' => ['deafness', 'hard of hearing', 'poor hearing']],
            ['name' => 'Ear Discharge', 'keywords' => ['fluid from ear', 'pus from ear', 'wet ear']],
            ['name' => 'Ringing in Ear', 'keywords' => ['tinnitus', 'buzzing in ear', 'ringing sound']],

            // Nose
            ['name' => 'Runny Nose', 'keywords' => ['rhinorrhea', 'dripping nose', 'watery nose']],
            ['name' => 'Nasal Congestion', 'keywords' => ['blocked nose', 'stuffed nose', 'stuffy nose']],
            ['name' => 'Sneezing', 'keywords' => ['sneeze', 'frequent sneezing']],
            ['name' => 'Nose Bleeding', 'keywords' => ['epistaxis', 'blood from nose', 'bleeding nose']],

            // Mouth & Throat
            ['name' => 'Sore Throat', 'keywords' => ['throat pain', 'scratchy throat', 'pharyngitis']],
            ['name' => 'Difficulty Swallowing', 'keywords' => ['dysphagia', 'trouble swallowing', 'painful swallowing']],
            ['name' => 'Dry Mouth', 'keywords' => ['xerostomia', 'lack of saliva', 'thirsty mouth']],
            ['name' => 'Bad Breath', 'keywords' => ['halitosis', 'foul breath', 'stinky mouth']],
            ['name' => 'Mouth Ulcer', 'keywords' => ['canker sore', 'mouth sore', 'oral ulcer']],
            ['name' => 'Toothache', 'keywords' => ['tooth pain', 'sore tooth', 'dental pain']],

            // Respiratory
            ['name' => 'Cough', 'keywords' => ['coughing', 'throat clearing']],
            ['name' => 'Dry Cough', 'keywords' => ['unproductive cough', 'tickly cough']],
            ['name' => 'Productive Cough', 'keywords' => ['wet cough', 'cough with phlegm', 'chesty cough']],
            ['name' => 'Shortness of Breath', 'keywords' => ['breathlessness', 'dyspnea', 'difficulty breathing']],
            ['name' => 'Wheezing', 'keywords' => ['whistling breath', 'breathing sound']],
            ['name' => 'Chest Tightness', 'keywords' => ['tight chest', 'pressure in chest', 'constricted chest']],
            ['name' => 'Chest Pain', 'keywords' => ['chest discomfort', 'angina', 'sharp chest pain']],

            // Cardiovascular
            ['name' => 'Palpitations', 'keywords' => ['racing heart', 'pounding heart', 'irregular heartbeat']],
            ['name' => 'High Blood Pressure', 'keywords' => ['hypertension', 'elevated BP']],
            ['name' => 'Low Blood Pressure', 'keywords' => ['hypotension', 'low BP']],
            ['name' => 'Swelling of Legs', 'keywords' => ['edema', 'swollen ankles', 'fluid retention in legs']],

            // Gastrointestinal
            ['name' => 'Abdominal Pain', 'keywords' => ['stomach ache', 'belly pain', 'tummy pain']],
            ['name' => 'Upper Abdominal Pain', 'keywords' => ['epigastric pain', 'upper belly pain']],
            ['name' => 'Lower Abdominal Pain', 'keywords' => ['lower belly pain', 'pelvic region pain']],
            ['name' => 'Heartburn', 'keywords' => ['acid reflux', 'burning chest', 'burning stomach']],
            ['name' => 'Acidity', 'keywords' => ['acid indigestion', 'sour stomach']],
            ['name' => 'Indigestion', 'keywords' => ['dyspepsia', 'upset stomach', 'poor digestion']],
            ['name' => 'Bloating', 'keywords' => ['gassiness', 'swollen belly', 'gas buildup']],
            ['name' => 'Nausea', 'keywords' => ['queasiness', 'feeling sick to stomach']],
            ['name' => 'Vomiting', 'keywords' => ['throwing up', 'emesis', 'throwing up food']],
            ['name' => 'Constipation', 'keywords' => ['hard stool', 'difficulty passing stool', 'irregular bowel']],
            ['name' => 'Diarrhea', 'keywords' => ['loose stools', 'watery stools', 'frequent bowel movements']],
            ['name' => 'Blood in Stool', 'keywords' => ['rectal bleeding', 'bloody stool', 'hematochezia']],
            ['name' => 'Black Stool', 'keywords' => ['dark stool', 'tarry stool', 'melena']],

            // Liver
            ['name' => 'Jaundice', 'keywords' => ['yellow skin', 'yellow eyes', 'yellowing of skin and eyes']],

            // Urinary
            ['name' => 'Burning Urination', 'keywords' => ['painful pee', 'burning when peeing', 'dysuria']],
            ['name' => 'Frequent Urination', 'keywords' => ['peeing often', 'excessive urination', 'polyuria']],
            ['name' => 'Painful Urination', 'keywords' => ['pain while peeing', 'discomfort during urination']],
            ['name' => 'Blood in Urine', 'keywords' => ['hematuria', 'bloody urine', 'red urine']],
            ['name' => 'Urinary Retention', 'keywords' => ['inability to empty bladder', 'urine blockage']],
            ['name' => 'Urinary Incontinence', 'keywords' => ['bladder leakage', 'losing control of urine']],

            // Musculoskeletal
            ['name' => 'Back Pain', 'keywords' => ['backache', 'lower back pain', 'spine pain']],
            ['name' => 'Neck Pain', 'keywords' => ['stiff neck', 'sore neck', 'cervical pain']],
            ['name' => 'Shoulder Pain', 'keywords' => ['sore shoulder', 'shoulder ache']],
            ['name' => 'Joint Pain', 'keywords' => ['arthralgia', 'aching joints', 'sore joints']],
            ['name' => 'Muscle Pain', 'keywords' => ['myalgia', 'muscle ache', 'sore muscles']],
            ['name' => 'Body Ache', 'keywords' => ['generalized pain', 'body pain', 'flu-like aches']],
            ['name' => 'Leg Pain', 'keywords' => ['sore legs', 'leg ache', 'cramping legs']],
            ['name' => 'Hand Pain', 'keywords' => ['hand ache', 'sore hands']],
            ['name' => 'Foot Pain', 'keywords' => ['foot ache', 'sore feet', 'heel pain']],
            ['name' => 'Joint Swelling', 'keywords' => ['swollen joints', 'puffy joints']],

            // Skin
            ['name' => 'Skin Rash', 'keywords' => ['rash', 'skin eruption', 'red spots']],
            ['name' => 'Itching', 'keywords' => ['pruritus', 'itchy skin', 'scratchy skin']],
            ['name' => 'Dry Skin', 'keywords' => ['flaky skin', 'scaling skin', 'rough skin']],
            ['name' => 'Hair Loss', 'keywords' => ['alopecia', 'thinning hair', 'baldness']],
            ['name' => 'Acne', 'keywords' => ['pimples', 'zits', 'breakouts']],
            ['name' => 'Skin Ulcer', 'keywords' => ['skin sore', 'open sore', 'ulceration']],

            // Endocrine
            ['name' => 'Excessive Thirst', 'keywords' => ['polydipsia', 'always thirsty', 'extreme thirst']],
            ['name' => 'Excessive Hunger', 'keywords' => ['polyphagia', 'constant hunger', 'ravenous appetite']],

            // Psychological
            ['name' => 'Anxiety', 'keywords' => ['nervousness', 'worry', 'panic', 'tension']],
            ['name' => 'Depression', 'keywords' => ['sadness', 'low mood', 'hopelessness', 'despondency']],
            ['name' => 'Stress', 'keywords' => ['mental pressure', 'overload', 'tension']],
            ['name' => 'Mood Changes', 'keywords' => ['mood swings', 'emotional instability']],
            ['name' => 'Irritability', 'keywords' => ['agitation', 'annoyance', 'impatience']],

            // Gynecology
            ['name' => 'Irregular Menstruation', 'keywords' => ['irregular periods', 'skipped periods', 'irregular cycle']],
            ['name' => 'Heavy Menstrual Bleeding', 'keywords' => ['menorrhagia', 'heavy periods', 'excessive bleeding']],
            ['name' => 'Painful Menstruation', 'keywords' => ['dysmenorrhea', 'period cramps', 'menstrual pain']],
            ['name' => 'Vaginal Discharge', 'keywords' => ['leukorrhea', 'unusual discharge']],
            ['name' => 'Pelvic Pain', 'keywords' => ['lower abdomen pain', 'pelvic ache']],

            // Obstetric
            ['name' => 'Pregnancy Checkup', 'keywords' => ['prenatal care', 'antenatal visit', 'pregnancy consultation']],

            // Pediatrics
            ['name' => 'Poor Feeding', 'keywords' => ['refusing milk', 'bad feeding in baby', 'poor nursing']],
            ['name' => 'Excessive Crying', 'keywords' => ['colic', 'continuous crying in baby']],

            // Others
            ['name' => 'Allergic Reaction', 'keywords' => ['allergy', 'hives', 'hypersensitivity']],
            ['name' => 'Insect Bite', 'keywords' => ['bug bite', 'mosquito bite', 'wasp sting']],
            ['name' => 'Animal Bite', 'keywords' => ['dog bite', 'cat bite', 'animal scratch']],
            ['name' => 'Burn Injury', 'keywords' => ['thermal burn', 'skin burn', 'scald']],
            ['name' => 'Trauma', 'keywords' => ['injury', 'accident', 'physical damage']],
            ['name' => 'Loss of Consciousness', 'keywords' => ['unconsciousness', 'blackout', 'unresponsive']],
        ];

        // Loop through and seed each symptom securely using updateOrCreate
        foreach ($symptoms as $index => $symptomData) {
            $name = $symptomData['name'];

            Symptom::updateOrCreate(
                [
                    'slug' => Str::slug($name),
                ],
                [
                    'name' => $name,
                    'description' => null,
                    'search_keywords' => $symptomData['keywords'], // Handled automatically by model casting
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}