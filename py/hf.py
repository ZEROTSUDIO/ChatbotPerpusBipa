import os
from huggingface_hub import login, create_repo, upload_folder

# 1. 🔐 Login using your HF token (get it from https://huggingface.co/settings/tokens)
login(os.getenv("HF_TOKEN"))  # <--- ganti dengan token kamu

# 2. 📁 Create repo on Hugging Face (if belum dibuat). Ganti nama repo sesuai keinginan.
repo_name = "Bipa-Classification"  # bebas, asal unik di akunmu
create_repo(repo_name, private=False)

# 3. 🚀 Upload the model folder
upload_folder(
    folder_path="./model",              # this path is correct from your working dir
    path_in_repo="",                    # upload everything into root of repo
    repo_id="ZEROTSUDIOS/" + repo_name,  # <--- ganti your_username
    repo_type="model"
)

print("✅ Upload completed!")
