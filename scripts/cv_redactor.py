import sys
import re
import fitz  # استدعاء مكتبة PyMuPDF
import shutil

def redact_pdf(input_path, output_path):
    # 1. تعريف أنماط البحث (Regex)
    # نمط البريد الإلكتروني
    email_pattern = r'[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+'
    
    # نمط أرقام الهواتف (يدعم الرموز الدولية، المسافات، والشرطات)
    phone_pattern = r'(?:(?:\+|00)\d{1,3}[\s\-\.]?)?(?:\d{2,4}[\s\-\.]?){2,4}\d{2,4}'
    
    # نمط روابط لينكدإن ومواقع التوظيف
    link_pattern = r'(?:https?:\/\/)?(?:www\.)?(?:linkedin\.com|github\.com)\/[^\s]+'

    patterns = [email_pattern, phone_pattern, link_pattern]

    # 2. فتح ملف الـ PDF
    doc = fitz.open(input_path)

    # 3. المرور على كل صفحة في الملف
    for page in doc:
        # استخراج النص من الصفحة
        text = page.get_text()
        
        for pattern in patterns:
            # البحث عن كل التطابقات للنمط الحالي في النص
            matches = re.finditer(pattern, text)
            
            for match in matches:
                text_to_redact = match.group().strip()
                
                # تخطي النصوص القصيرة جداً التي قد يتم التقاطها بالخطأ
                if len(text_to_redact) < 5:
                    continue
                    
                # البحث عن إحداثيات (موقع) هذا النص في الصفحة
                areas = page.search_for(text_to_redact)
                
                for area in areas:
                    # إضافة تظليل أسود (Redaction Annotation) فوق موقع النص
                    page.add_redact_annot(area, fill=(0, 0, 0))
        
        # تطبيق التظليل الفعلي لدمجه في الصفحة بحيث لا يمكن إزالته
        page.apply_redactions()

    # 4. حفظ الملف المظلل في المسار الجديد
    doc.save(output_path)
    doc.close()

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("Error: Missing arguments")
        sys.exit(1)
        
    input_file = sys.argv[1]
    output_file = sys.argv[2]
    
    try:
        # التحقق من نوع الملف
        if input_file.lower().endswith('.pdf'):
            redact_pdf(input_file, output_file)
            print("Success: PDF redacted")
        else:
            # إذا كان الملف Word (docx) أو غيره، نقوم بنسخه كما هو حالياً كحل مؤقت
            # (يمكننا إضافة دعم تظليل ملفات الوورد لاحقاً إذا احتجت ذلك)
            shutil.copyfile(input_file, output_file)
            print("Success: File copied (Non-PDF)")
            
    except Exception as e:
        print(f"Error: {str(e)}")
        sys.exit(1)